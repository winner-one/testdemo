<?php

namespace app\admin\controller\shopro\order;

use addons\shopro\library\Export;
use addons\shopro\model\Config as ModelConfig;
use app\admin\model\shopro\order\OrderExpress;
use think\Db;
use think\Config;
use app\common\controller\Backend;
use app\admin\controller\shopro\Base;

/**
 * 订单管理
 *
 * @icon fa fa-circle-o
 */
class Order extends Base
{
    protected $noNeedRight = ['getType', 'getExpress'];

    /**
     * Order模型对象
     * @var \app\admin\model\shopro\order\Order
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();

        // 手动加载语言包
        $this->loadlang('shopro/order/order_item');
        $this->loadlang('shopro/dispatch/dispatch');
        $this->loadlang('shopro/goods/goods');

        $this->model = new \app\admin\model\shopro\order\Order;
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("payTypeList", $this->model->getPayTypeList());
        $this->view->assign("platformList", $this->model->getPlatformList());
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }

            $nobuildfields = ['status', 'aftersale_sn', 'dispatch_type', 'goods_type', 'nickname', 'user_phone', 'goods_title'];
            list($where, $sort, $order, $offset, $limit) = $this->custombuildparams(null, $nobuildfields);

            if ($this->auth->store_id) {
                $wheres['store_id'] = $this->auth->store_id;
            } else {
                $wheres = [];
            }
            $total = $this->buildSearchOrder()
                ->where($where)
                ->where($wheres)
                ->order($sort, $order)
                ->count();

            $list = $this->buildSearchOrder()
                ->where($where)
                ->where($wheres)
                ->with(['user', 'item'])
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            // foreach ($list as $row) {
            //     $row->visible(['id','type','order_sn','user','item','user_id','phone','consignee','province_name','city_name','area_name','address','status','memo','remark','total_amount','total_fee','createtime']);
            // }

            $list = collection($list)->toArray();

            foreach ($list as $key => $od) {
                // 处理 未支付订单 的 订单 item status_code 状态
                $list[$key] = $this->model->setOrderItemStatusByOrder($od);
            }

            $result = array("total" => $total, "rows" => $list);

            return $this->success('操作成功', null, $result);
        }
        return $this->view->fetch();
    }


    public function export()
    {
        $export = new Export();

        $nobuildfields = ['status', 'aftersale_sn', 'dispatch_type', 'goods_type', 'nickname', 'user_phone'];
        list($where, $sort, $order, $offset, $limit) = $this->custombuildparams(null, $nobuildfields);

        $list = $this->buildSearchOrder()
            ->where($where)
            ->with(['user', 'item'])
            ->order($sort, $order)
            ->select();

        $expCellName = [
            'order_id' => 'Id',
            'order_sn' => '订单号',
            'type_text' => '订单类型',
            'status_text' => '订单状态',
            'pay_type_text' => '支付类型',
            'paytime_text' => '支付时间',
            'platform_text' => '交易平台',
            'user_info' => '用户信息',
            'total_amount' => '订单总金额',
            'discount_fee' => '优惠金额',
            'pay_fee' => '实际支付金额',
            'score_fee' => '积分支付数量',
            'activity_type_text' => '营销类型',
            'goods_title' => '商品名称',
            'goods_original_price' => '商品原价',
            'goods_price' => '商品价格',
            'goods_sku_text' => '商品规格',
            'goods_num' => '购买数量',
            'dispatch_status_text' => '发货状态',
            'dispatch_fee' => '发货费用',
            'dispatch_type_text' => '发货方式',
            'aftersale_refund' => '售后/退款',
            'comment_status_text' => '评价状态',
            'refund_fee' => '退款金额',
            'refund_msg' => '退款原因',
            'express_name' => '快递公司',
            'express_no' => '快递单号',
        ];

        $newList = [];
        foreach ($list as $key => $order) {
            $data = [
                'order_id' => $order['id'],
                'order_sn' => $order['order_sn'],
                'type_text' => $order['type_text'],
                'status_text' => $order['status_text'],
                'pay_type_text' => $order['pay_type_text'],
                'paytime_text' => $order['paytime_text'],
                'platform_text' => $order['platform_text'],
                'user_info' => $order['user'] ? ($order['user']['nickname'] . '-' . $order['user']['mobile']) : '',
                'total_amount' => $order['total_amount'],
                'discount_fee' => $order['discount_fee'],
                'pay_fee' => $order['pay_fee'],
                'score_fee' => $order['score_fee'],
            ];
            foreach ($order['item'] as $k => $item) {
                $itemData = [
                    'activity_type_text' => $item['activity_type_text'],
                    'goods_title' => $item['goods_title'],
                    'goods_original_price' => $item['goods_original_price'],
                    'goods_price' => $item['goods_price'],
                    'goods_sku_text' => $item['goods_sku_text'],
                    'goods_num' => $item['goods_num'],
                    'dispatch_status_text' => $item['dispatch_status_text'],
                    'dispatch_fee' => $item['dispatch_fee'],
                    'dispatch_type_text' => $item['dispatch_type_text'],
                    'aftersale_refund' => $item['aftersale_status_text'] . '/' . $item['refund_status_text'],
                    'comment_status_text' => $item['comment_status_text'],
                    'refund_fee' => $item['refund_fee'],
                    'refund_msg' => $item['refund_msg'],
                    'express_name' => $item['express_name'],
                    'express_no' => $item['express_no'],
                ];

                $newList[] = array_merge($data, $itemData);
            }
        }

        // 增加合计
        $newList[] = [
            'order_id' => "订单总数：" . count($list) . "；订单总金额：￥" . array_sum(array_column($list, 'total_amount')) . "；优惠总金额：￥" . array_sum(array_column($list, 'discount_fee')) . "；实际支付总金额：￥" . array_sum(array_column($list, 'pay_fee')) . "；支付总积分：" . array_sum(array_column($list, 'score_fee'))
        ];

        $export->exportExcel('订单列表-' . date('Y-m-d H:i:s'), $expCellName, $newList);
    }


    // 获取要查询的订单类型
    public function getType()
    {
        $type = $this->model->getTypeList();
        $pay_type = $this->model->getPayTypeList();
        $platform = $this->model->getPlatformList();
        $dispatch_type = (new \app\admin\model\shopro\dispatch\Dispatch)->getTypeList();
        $activity_type = (new \app\admin\model\shopro\activity\Activity)->getTypeList();
        $goods_type = (new \app\admin\model\shopro\goods\Goods)->getTypeList();

        $result = [
            'type' => $type,
            'pay_type' => $pay_type,
            'platform' => $platform,
            'dispatch_type' => $dispatch_type,
            'activity_type' => $activity_type,
            'goods_type' => $goods_type
        ];

        $data = [];
        foreach ($result as $key => $list) {
            $data[$key][] = ['name' => '全部', 'type' => 'all'];

            foreach ($list as $k => $v) {
                $data[$key][] = [
                    'name' => $v,
                    'type' => $k
                ];
            }
        }

        return $this->success('操作成功', null, $data);
    }


    public function detail($id)
    {
        if ($this->request->isAjax()) {
            $row = $this->model->withTrashed()->with(['user', 'item.store'])->where('id', $id)->find();
            if (!$row) {
                $this->error(__('No Results were found'));
            }

            $row->express = OrderExpress::with(['item' => function ($query) use ($id) {
                return $query->where('order_id', $id);
            }, 'log'])->where('order_id', $id)->select();

            // 处理未支付 item status_code
            $row = $this->model->setOrderItemStatusByOrder($row);

            return $this->success('获取成功', null, [
                'order' => $row,
                'item' => $row['item'],
                'express' => $row['express']
            ]);
        }

        $this->assignconfig('id', $id);
        return $this->view->fetch();
    }


    // 发货
    public function send($id, $item_id = '', $express_id = 0)
    {
        if ($this->request->isAjax()) {
            $express_name = $this->request->post('express_name', '');
            $express_code = $this->request->post('express_code', '');
            $express_no = $this->request->post('express_no', '');
            $item_id = $item_id ? explode(',', $item_id) : [];
            if (!$item_id) {
                $this->error('请选择要发货的商品');
            }
            if (!$express_name || !$express_code || !$express_no) {
                $this->error('请填写完整发货信息');
            }

            $order = $this->model->payed()->where('id', $id)->find();

            if (!$order) {
                $this->error('订单不存在或未支付');
            }

            // 编辑包裹
            $orderExpress = null;
            $need_subscribe = true;
            if ($express_id) {
                // 修改包裹
                $orderExpress = OrderExpress::where('id', $express_id)->find();
                if (!$orderExpress) {
                    $this->error('包裹不存在');
                }

                if ($orderExpress->express_no == $express_name && $orderExpress->express_code == $express_code) {
                    // 没有编辑快递信息，不需要重新订阅快递
                    $need_subscribe = false;
                }
            }

            // 查询要发货的商品
            $where = [
                'order_id' => $id,
                'id' => ['in', $item_id],
                'dispatch_type' => 'express'        // 必须是物流快递的商品
            ];
            $dispatchWhere = [
                \app\admin\model\shopro\order\OrderItem::DISPATCH_STATUS_NOSEND,
            ];
            if ($express_id) {
                // 修改可以修改已发货的 商品
                $dispatchWhere[] = \app\admin\model\shopro\order\OrderItem::DISPATCH_STATUS_SENDED;
            }
            $where['dispatch_status'] = ['in', $dispatchWhere];

            $item_list = \app\admin\model\shopro\order\OrderItem::where($where)->select();

            if (!$item_list) {
                $this->error('没有要发货的订单商品');
            }

            $orderExpress = Db::transaction(function () use ($order, $item_list, $orderExpress, $express_name, $express_code, $express_no, $item_id) {
                foreach ($item_list as $key => $item) {
                    $order->sendItem($order, $item, [
                        "express_name" => $express_name,
                        "express_code" => $express_code,
                        "express_no" => $express_no,
                        "oper" => $this->auth->getUserInfo(),
                        "oper_type" => 'admin',
                    ]);
                }

                if (!$orderExpress) {
                    // 添加包裹
                    $orderExpress = new OrderExpress();
                    $orderExpress->user_id = $order->user_id;
                    $orderExpress->order_id = $order->id;
                } else {
                    // 查询包裹老的商品,并且不在这次所选择之中，改为未发货
                    \app\admin\model\shopro\order\OrderItem::where('order_id', $order['id'])
                        ->where('id', 'not in', $item_id)
                        ->where('express_no', $orderExpress->express_no)
                        ->where('express_code', $orderExpress->express_code)->update([
                            'express_name' => null,
                            'express_code' => null,
                            'express_no' => null,
                            'dispatch_status' => \app\admin\model\shopro\order\OrderItem::DISPATCH_STATUS_NOSEND
                        ]);
                }

                $orderExpress->express_name = $express_name;
                $orderExpress->express_code = $express_code;
                $orderExpress->express_no = $express_no;
                $orderExpress->save();

                // 查询已经没有商品的包裹，并且删除
                OrderExpress::whereNotExists(function ($query) use ($order) {
                    $order_table_name = (new OrderExpress())->getQuery()->getTable();
                    $table_name = (new \app\admin\model\shopro\order\OrderItem())->getQuery()->getTable();
                    $query->table($table_name)->where($table_name . '.express_no=' . $order_table_name . '.express_no')
                        ->where($table_name . '.express_code=' . $order_table_name . '.express_code')
                        ->where('order_id', $order['id']);
                })->where('order_id', $order['id'])->delete();

                return $orderExpress;
            });

            // 将快递信息上报快递 api
            if ($need_subscribe) {
                try {
                    $expressLib = new \addons\shopro\library\Express();
                    $expressLib->subscribe([
                        'express_code' => $express_code,
                        'express_no' => $express_no
                    ], $orderExpress, $order);
                } catch (\Exception $e) {
                    $sub_msg = $e->getMessage();
                }
            }

            // 重新获取订单
            $order = $this->model->with(['item'])->where('id', $id)->find();

            return $this->success('发货成功' . (isset($sub_msg) ? ': ' . $sub_msg : ''), null, $order);
        }
    }


    /**
     * 获取物流快递信息
     */
    public function getExpress($express_id = 0)
    {
        $type = $this->request->get('type');

        // 获取包裹
        $orderExpress = OrderExpress::with('order')->where('id', $express_id)->find();
        if (!$orderExpress) {
            return $this->error('包裹不存在');
        }

        $expressLib = new \addons\shopro\library\Express();

        try {
            if ($type == 'subscribe') {
                // 重新订阅
                $expressLib->subscribe([
                    'express_code' => $orderExpress['express_code'],
                    'express_no' => $orderExpress['express_no']
                ], $orderExpress, $orderExpress->order);
            } else {
                // 手动查询
                $result = $expressLib->search([
                    'express_code' => $orderExpress['express_code'],
                    'express_no' => $orderExpress['express_no']
                ], $orderExpress, $orderExpress->order);

                // 差异更新物流信息
                $expressLib->checkAndAddTraces($orderExpress, $result);
            }

            $order = $this->model->with(['item'])->where('id', $orderExpress['order_id'])->find();
        } catch (\Exception $e) {
            return $this->error(($type == 'subscribe' ? '订阅失败' : '刷新失败') . $e->getMessage());
        }

        return $this->success(($type == 'subscribe' ? '订阅成功' : '刷新成功'), null, $order);
    }


    // public function aftersaleFinish($id, $item_id)
    // {
    //     if ($this->request->isAjax()) {

    //         $where = [
    //             'order_id' => $id,
    //             'id' => $item_id,
    //             'aftersale_status' => \app\admin\model\shopro\order\OrderItem::AFTERSALE_STATUS_AFTERING
    //         ];

    //         $order = $this->model->where('status', 'in', [
    //                 \app\admin\model\shopro\order\Order::STATUS_PAYED,
    //                 \app\admin\model\shopro\order\Order::STATUS_FINISH
    //             ]
    //         )
    //             ->where('id', $id)->find();
    //         $item = \app\admin\model\shopro\order\OrderItem::where($where)->find();

    //         if (!$order || !$item) {
    //             $this->error('订单不存在或不是售后订单');
    //         }

    //         Db::transaction(function () use ($order, $item) {
    //             $item->aftersale_status = \app\admin\model\shopro\order\OrderItem::AFTERSALE_STATUS_OK;    // 售后完成
    //             $item->save();

    //             \addons\shopro\model\OrderAction::operAdd($order, $item, $this->auth->getUserInfo(), 'admin', '管理员售后完成订单');
    //         });

    //         $item_list = \app\admin\model\shopro\order\OrderItem::where(['order_id' => $id])->select();
    //         return $this->success('操作成功', null, $item_list);
    //     }
    // }


    // /**
    //  * 拒绝退款
    //  */
    // public function refundRefuse($id = 0, $item_id = 0)
    // {
    //     if ($this->request->isAjax()) {
    //         $refund_msg = $this->request->post('refund_msg');

    //         $where = [
    //             'order_id' => $id,
    //             'id' => $item_id,
    //             'refund_status' => \app\admin\model\shopro\order\OrderItem::REFUND_STATUS_ING
    //         ];

    //         $order = $this->model->where('status', 'in', [
    //                 \app\admin\model\shopro\order\Order::STATUS_PAYED,
    //                 \app\admin\model\shopro\order\Order::STATUS_FINISH
    //             ]
    //         )->where('id', $id)->find();

    //         $item = \app\admin\model\shopro\order\OrderItem::where($where)->find();

    //         if (!$order || !$item) {
    //             $this->error('订单不存在或不是退款订单');
    //         }

    //         Db::transaction(function () use ($order, $item, $refund_msg) {
    //             // 订单退款拒绝前
    //             $data = ['order' => $order, 'item' => $item];
    //             \think\Hook::listen('order_refund_refuse_before', $data);

    //             $item->refund_status = \app\admin\model\shopro\order\OrderItem::REFUND_STATUS_REFUSE;    // 拒绝退款
    //             $item->refund_msg = $refund_msg;
    //             $item->save();

    //             \addons\shopro\model\OrderAction::operAdd($order, $item, $this->auth->getUserInfo(), 'admin', '管理员拒绝订单退款');

    //             // 订单退款拒绝后
    //             $data = ['order' => $order, 'item' => $item];
    //             \think\Hook::listen('order_refund_refuse_after', $data);
    //         });

    //         $item_list = \app\admin\model\shopro\order\OrderItem::where(['order_id' => $id])->select();
    //         return $this->success('操作成功', null, $item_list);
    //     }
    // }


    /**
     * 同意退款
     */
    public function refund($id = 0, $item_id = 0)
    {
        if ($this->request->isAjax()) {
            $refund_money = round($this->request->post('refund_money', 0), 2);

            if ($refund_money <= 0) {
                $this->error('退款金额必须大于 0');
            }

            $order = $this->model->where('status', 'in', [
                \app\admin\model\shopro\order\Order::STATUS_PAYED,
                \app\admin\model\shopro\order\Order::STATUS_FINISH
            ])
                ->with('item')->where('id', $id)->find();

            if (!$order) {
                $this->error('订单不存在或不可退款');
            }

            $items = $order->item;
            $items = array_column($items, null, 'id');

            // 当前订单已退款总金额
            $refunded_money = array_sum(array_column($items, 'refund_fee'));
            // 剩余可退款金额
            $refund_surplus_money = $order->pay_fee - $refunded_money;
            // 如果退款金额大于订单支付总金额
            if ($refund_money > $refund_surplus_money) {
                $this->error('退款总金额不能大于实际支付金额');
            }

            if ($item_id) {
                $item = $items[$item_id];
                if (!$item || in_array($item['refund_status'], [
                    \app\admin\model\shopro\order\OrderItem::REFUND_STATUS_OK,
                    \app\admin\model\shopro\order\OrderItem::REFUND_STATUS_FINISH,
                ])) {
                    $this->error('订单商品已退款，不能重复退款');
                }
            } else {
                $is_refund = false;
                foreach ($items as $key => $it) {
                    if (in_array($it['refund_status'], [
                        \app\admin\model\shopro\order\OrderItem::REFUND_STATUS_OK,
                        \app\admin\model\shopro\order\OrderItem::REFUND_STATUS_FINISH,
                    ])) {
                        // 已退款
                        unset($items[$key]);
                    } else {
                        $is_refund = true;
                    }
                }
                $items = array_values($items);

                if (!$is_refund) {
                    $this->error('订单已退款，不能重复退款');
                }
            }

            Db::transaction(function () use ($order, $items, $item_id, $refund_money, $refund_surplus_money) {
                if ($item_id) {
                    // 单个商品退款
                    $item = $items[$item_id];
                    \app\admin\model\shopro\order\Order::startRefund($order, $item, $refund_money, $this->auth->getUserInfo(), '管理员操作退款');
                } else {
                    // 全部退款
                    // 未退款 item 商品总金额
                    $goods_total_amount = 0;
                    foreach ($items as $ke => $it) {
                        $goods_total_amount += ($it['goods_price'] * $it['goods_num']);
                    }

                    $current_refunded_money = 0;
                    for ($i = 0; $i < count($items); $i++) {
                        if ($i == (count($items) - 1)) {
                            // 最后一条,全部退完
                            $current_refund_money = $refund_money - $current_refunded_money;
                        } else {
                            // 按比例计算当前 item 应退金额
                            $current_refund_money = round($refund_money * (($items[$i]['goods_price'] * $it['goods_num']) / $goods_total_amount), 2);
                        }
                        if (($current_refunded_money + $current_refund_money) > $refund_money) {
                            $current_refund_money = $refund_money - $current_refunded_money;
                        }

                        if ($current_refund_money > 0) {
                            $current_refunded_money += $current_refund_money;

                            \app\admin\model\shopro\order\Order::startRefund($order, $items[$i], $current_refund_money, $this->auth->getUserInfo(), '管理员操作退款');
                        }
                    }
                }
            });

            $item_list = \app\admin\model\shopro\order\OrderItem::where(['order_id' => $id])->select();
            return $this->success('操作成功', null, $item_list);
        }
    }


    // 取消订单
    public function cancel($id)
    {
        if ($this->request->isAjax()) {
            $order = $this->model->where('id', $id)->nopay()->find();
            if (!$order) {
                $this->error('订单不存在或已取消');
            }

            $order = $order->doCancel($order, $this->auth->getUserInfo(), 'admin');

            return $this->success('操作成功', null, $order);
        }
    }


    // 修改收货人信息
    public function editConsignee($id)
    {
        if ($this->request->isAjax()) {
            $params = $this->request->post();
            extract($params);

            $row = $this->model->get($id);
            if (!$row) {
                $this->error('订单不存在');
            }

            try {
                if ($this->modelValidate) {
                    $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                    $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                    $row->validateFailException(true)->validate($validate);
                }

                $result = $row->save([
                    'consignee' => $consignee,
                    'phone' => $phone,
                    'province_id' => $province_id,
                    'province_name' => $province_name,
                    'city_id' => $city_id,
                    'city_name' => $city_name,
                    'area_id' => $area_id,
                    'area_name' => $area_name,
                    'address' => $address,
                ], ['id' => $id]);
            } catch (ValidateException $e) {
                $this->error($e->getMessage());
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
            if ($result !== false) {
                $order = $this->model->get($id);
                $this->success('修改成功', null, $order);
            } else {
                $this->error(__('No rows were updated'));
            }
        }
    }

    // 编辑商家备注
    public function editMemo($id)
    {
        if ($this->request->isAjax()) {
            $memo = $this->request->post('memo');

            $order = $this->model->get($id);
            if (!$order) {
                $this->error('订单不存在');
            }

            $order->memo = $memo;
            $order->save();

            \addons\shopro\model\OrderAction::operAdd($order, null, $this->auth->getUserInfo(), 'admin', "修改备注：" . $memo);

            return $this->success('操作成功', null, $order);
        }
    }


    // 获取订单操作记录
    public function actions($id)
    {
        $actions = \app\admin\model\shopro\order\OrderAction::with('oper')->where('order_id', $id)->select();

        foreach ($actions as $key => $action) {
            $action = $action->toArray();
            if ($action['oper_type'] == 'admin') {
                $oper = [
                    'id' => $action['oper_id'],
                    'name' => $action['oper'] ? $action['oper']['nickname'] : ''
                ];
            } else if ($action['oper_type'] == 'user') {
                $oper = [
                    'id' => $action['oper_id'],
                    'name' => '用户'
                ];
            } else if ($action['oper_type'] == 'system') {
                $oper = [
                    'id' => $action['oper_id'],
                    'name' => '系统'
                ];
            } else {
                $oper = null;
            }

            $action['oper'] = $oper;
            $actions[$key] = $action;
        }

        return $this->success('操作成功', null, $actions);
    }


    // 构建查询条件
    private function buildSearchOrder()
    {
        $filter = $this->request->get("filter", '');
        $filter = (array)json_decode($filter, true);
        $filter = $filter ? $filter : [];

        $dispatch_type = isset($filter['dispatch_type']) ? $filter['dispatch_type'] : 'all';
        $status = isset($filter['status']) ? $filter['status'] : 'all';
        $goods_type = isset($filter['goods_type']) ? $filter['goods_type'] : 'all';
        $aftersale_sn = isset($filter['aftersale_sn']) ? $filter['aftersale_sn'] : '';
        $nickname = isset($filter['nickname']) ? $filter['nickname'] : '';
        $mobile = isset($filter['user_phone']) ? $filter['user_phone'] : '';
        $goods_title = isset($filter['goods_title']) ? $filter['goods_title'] : '';

        $name = $this->model->getQuery()->getTable();
        $tableName = $name . '.';

        $orders = $this->model->withTrashed();

        if ($nickname || $mobile) {
            $orders = $orders->whereExists(function ($query) use ($nickname, $mobile, $tableName) {
                $userTableName = (new \app\admin\model\User())->getQuery()->getTable();

                $query = $query->table($userTableName)->where($userTableName . '.id=' . $tableName . 'user_id');

                if ($nickname) {
                    $query = $query->where('nickname', 'like', "%{$nickname}%");
                }

                if ($mobile) {
                    $query = $query->where('mobile', 'like', "%{$mobile}%");
                }

                return $query;
            });
        }

        // 售后单号
        if ($aftersale_sn) {
            $orders = $orders->whereExists(function ($query) use ($aftersale_sn, $tableName) {
                $itemTableName = (new \app\admin\model\shopro\order\Aftersale())->getQuery()->getTable();

                $query->table($itemTableName)->where($itemTableName . '.order_id=' . $tableName . 'id')
                    ->where('aftersale_sn', $aftersale_sn);
            });
        }


        // 快递方式 || 商品类型 (同一个表，写在一起)
        if ($dispatch_type != 'all' || $goods_type != 'all' || $goods_title) {
            $orders = $orders->whereExists(function ($query) use ($dispatch_type, $goods_type, $goods_title, $tableName) {
                $itemTableName = (new \app\admin\model\shopro\order\OrderItem())->getQuery()->getTable();

                $query = $query->table($itemTableName)->where($itemTableName . '.order_id=' . $tableName . 'id');

                if ($dispatch_type != 'all') {
                    $query = $query->where('dispatch_type', $dispatch_type);
                }

                if ($goods_type != 'all') {
                    $query = $query->where('goods_type', $goods_type);
                }

                if ($goods_title) {
                    $query = $query->where('goods_title', 'like', "%{$goods_title}%");
                }

                return $query;
            });
        }

        // 订单状态
        if ($status != 'all') {
            if (in_array($status, ['invalid', 'cancel', 'nopay', 'nosend', 'noget', 'nocomment', 'aftersale', 'refund', 'payed', 'finish'])) {
                if (in_array($status, ['nosend', 'noget', 'nocomment', 'aftersale', 'refund'])) {
                    $orders = $orders->payed();
                }
                $status = $status == 'refund' ? 'refundStatus' : $status;
                $orders = $orders->{$status}();
            }
        }

        return $orders;
    }
}
