<?php

namespace addons\shopro\model\store;

use addons\shopro\model\ActivityGroupon;
use think\Model;
use addons\shopro\exception\Exception;
use addons\shopro\library\traits\model\order\OrderScope;
use think\Db;
use think\Queue;
use traits\model\SoftDelete;
use addons\shopro\library\traits\model\order\OrderOper;
use addons\shopro\library\traits\model\order\OrderStatus;
use addons\shopro\model\Order as BaseOrder;
use addons\shopro\model\OrderItem;
use addons\shopro\model\Store;
use addons\shopro\model\User;
use addons\shopro\model\Verify;

/**
 * 商户订单模型
 */
class Order extends BaseOrder
{
    // 
    protected $append = [
        'status_code',
        'status_name',
        'ext_arr'
    ];


    /**
     * 获取订单列表(已付款的),覆盖点 baseOrder 中 orderOper getList 方法
     */
    public static function getList($params) {
        $user = User::info();
        $store = Store::info();

        // 查询分页列表
        $orders = self::buildQueryList($params)->paginate(10);

        // 查询搜索条件下的总金额
        $total_money = self::buildQueryList($params)->sum('pay_fee');

        $result = [
            'total_num' => $orders->total(),
            'total_money' => $total_money,
            'result' => $orders
        ];
        
        return $result;
    }


    /**
     * 要单独查询总金额
     */
    private static function buildQueryList($params) {
        $date_type = $params['date_type'] ?? 'today';
        $date = $params['date'] ?? [];
        $type = $params['type'] ?? 'all';

        // 只查付款的订单
        $order = (new self())->with('item')->payed();

        switch ($type) {
            case 'all':
                $order = $order->store();
                break;
            case 'nosend':
                $order = $order->storeNosend();
                break;
            case 'noget':
                $order = $order->storeNoget();
                break;
            case 'finish':
                $order = $order->storeFinish();
                break;
        }

        switch ($date_type) {
            case 'today':
                $start = strtotime(date('Y-m-d'));
                $end = strtotime(date('Y-m-d', (time() + 86400)));
                break;
            case 'yesterday':
                $start = strtotime(date('Y-m-d', (time() - 86400)));
                $end = strtotime(date('Y-m-d'));
                break;
            case 'week':
                // $start = strtotime(date('Y-m-d', (time() - (86400 * 6))));  // 减 6 天
                $start = strtotime(date('Y-m-d', strtotime("-1 week Monday")));     // 周一开始
                $end = strtotime(date('Y-m-d', (time() + 86400)));          // 到今天结束
                break;
            case 'month':
                // $start = strtotime(date('Y-m-d', (time() - (86400 * 30))));  // 减 30 天
                $start = strtotime(date('Y-m-d', mktime(0, 0, 0, date("m"), 1, date("Y"))));  // 减 30 天
                $end = strtotime(date('Y-m-d', (time() + 86400)));          // 到今天结束
                break;
            case 'custom':
                $date_start = $date[0] ?? date('Y-m-d');        // 默认查 today
                $date_end = $data[1] ?? date('Y-m-d');          // 默认查 today
                $start = strtotime($date_start);
                $end = strtotime($date_end) + 86400;
                break;
            default:
                $start = strtotime(date('Y-m-d'));
                $end = strtotime(date('Y-m-d', (time() + 86400)));
                break;
        }

        $order = $order->where('createtime', '>=', $start)
                ->where('createtime', '<', $end)
                ->order('id', 'desc');

        return $order;
    }


    /**
     * 订单详情     覆盖点 baseOrder 中 orderOper detail 方法
     */
    public static function detail($params)
    {
        $user = User::info();
        $store = Store::info();
        extract($params);

        $order = (new self())->with('item')->payed()->store();

        if (isset($order_sn)) {
            $order = $order->where('order_sn', $order_sn);
        }
        if (isset($id)) {
            $order = $order->where('id', $id);
        }

        $order = $order->find();

        if (!$order) {
            throw new Exception('订单不存在');
        }

        return $order;
    }


    /**
     * 订单发货 
     */
    public static function operSend($params) {
        $user = User::info();
        $store = Store::info();
        extract($params);

        $order = (new self())->with(['item' => function ($query) {
            $query->where('dispatch_status', \addons\shopro\model\OrderItem::DISPATCH_STATUS_NOSEND)
                ->where('refund_status', 'not in', [OrderItem::REFUND_STATUS_OK, OrderItem::REFUND_STATUS_FINISH]);
        }])->payed()->where('id', $id)->find();

        if (!$order || !$order->item) {
            throw new Exception('订单不存在');
        }

        if (!$order->item) {
            throw new Exception('订单已发货');
        }

        foreach ($order['item'] as $key => $value) {
            if ($value['dispatch_type'] == 'express') {         //物流配送需要到后台发货
                throw new Exception('发货失败，请到后台管理发货');
            }
        }

        // 订单里面这个门店相关的全部发货
        Db::transaction(function () use ($order) {
            $order->storeOrderSend($order);
        });
    }


    /**
     * 商家核销订单, 覆盖点 baseOrder 中 orderOper operConfirm 方法
     */
    public static function operConfirm($params)
    {
        $user = User::info();
        $store = Store::info();
        extract($params);
        $id = $params['id'] ?? 0;
        $codes = $params['codes'] ?? [];

        // 查询核销码
        $verifies = Verify::canUse()->with(['order', 'orderItem'])->where('type', 'verify')
                    ->where('code', 'in', $codes)->select();

        if (!$verifies) {
            throw new Exception('核销码不可用');
        }

        Db::transaction(function () use ($store, $verifies) {
            $check_num = 0;
            foreach ($verifies as $key => $verify) {
                $order = $verify['order'];
                $orderItem = $verify['orderItem'];

                if (!$order || $order['status'] <= 0) {
                    throw new Exception('订单不存在');
                }

                if (!$orderItem || $orderItem['store_id'] != $store['id']) {
                    throw new Exception('您不能操作该核销码');
                }

                if ($orderItem['dispatch_status'] != OrderItem::DISPATCH_STATUS_SENDED) {
                    throw new Exception('当前商品已核销');
                }

                if (in_array($orderItem['refund_status'], [OrderItem::REFUND_STATUS_OK, OrderItem::REFUND_STATUS_FINISH])) {
                    throw new Exception('当前商品已退款');
                }
                
                $check_num ++;
                $order->verifyGeted($order, $orderItem, $verify, ['oper_type' => 'store']);
            }

            if ($check_num <= 0) {
                // 一个核销码也没核销
                throw new Exception('核销失败，无效的核销码');
            }
        });
    }


    /* -------------------------- 访问器 ------------------------ */

    /**
     * 覆盖 BaseOrder 中 OrderStatus trait getStatus 方法
     */
    protected function getStatus($data, $type)
    {
        $btns = [];
        $status_name = '';
        $status_desc = '';

        switch ($this->status_code) {
            case 'cancel':
                $status_name = '已取消';
                break;
            case 'invalid':
                $status_name = '交易关闭';
                break;
            case 'nopay':
                $status_name = '待付款';
                break;
            // 收完货就算已完成
            case 'nocomment':
            case 'commented':
            case 'finish':
                $status_name = '已完成';
                break;
            case 'noget':
                $dispatchType = [];
                foreach ($this->item as $key => $item) {
                    // 获取 item status
                    $dispatchType[] = $item['dispatch_type'];
                }
                $dispatchType = array_unique(array_filter($dispatchType));  // 过滤重复，过滤空值

                $status_name = '待收货';
                if (in_array('selfetch', $dispatchType)) {
                    $status_name = '待提货/到店';
                } else if (in_array('selfetch', $dispatchType)) {
                    $status_name = '配送中';
                }
                break;
            case 'nosend':
                $dispatchType = [];
                foreach ($this->item as $key => $item) {
                    // 获取 item status
                    $dispatchType[] = $item['dispatch_type'];
                }
                $dispatchType = array_unique(array_filter($dispatchType));  // 过滤重复，过滤空值

                $status_name = '待发货';
                if (in_array('store', $dispatchType)) {
                    $status_name = '待配送';
                } else if (in_array('selfetch', $dispatchType)) {
                    $status_name = '待备货';
                }
                break;
            case 'refund_finish':
                $status_name = '退款完成';
                break;
            case 'refund_ing':
                $status_name = '退款处理中';
                break;
            case 'groupon_ing':
                $status_name = '等待成团';
                break;
            case 'groupon_invalid':
                $status_name = '拼团失败';
                break;
        }

        return $type == 'status_name' ? $status_name : ($type == 'btns' ? $btns : $status_desc);
    }


    /**
     * 获取支付成功之后的子状态, 门店订单，覆盖 baseOrder model orderStatus trait 中的 getPayedStatusCode 方法
     */
    public function getPayedStatusCode($data)
    {
        $status_code = '';

        // 循环判断 item 状态
        $itemStatusCode = [];
        foreach ($this->item as $key => $item) {
            // 获取 item status
            $itemStatusCode[] = (new OrderItem)->getBaseStatusCode($item->toArray(), 'order');
        }

        // 取出不重复不为空的 status_code
        $statusCodes = array_unique(array_filter($itemStatusCode));

        if (in_array('nosend', $statusCodes)) {
            $status_code = 'nosend';
        } else if (in_array('noget', $statusCodes)) {
            $status_code = 'noget';
        } else if (in_array('nocomment', $statusCodes)) {
            // 存在待评价，就是待评价
            $status_code = 'nocomment';
        } else if (in_array('commented', $statusCodes)) {
            // 存在已评价，就是已评价
            $status_code = 'commented';        // 走这里，说明只评价了一部分
        } else if (in_array('refund_finish', $statusCodes)) {
            // 都在退款完成
            $status_code = 'refund_finish';
        } else if (in_array('refund_ing', $statusCodes)) {
            // 都在退款中
            $status_code = 'refund_ing';
        } // 售后都不在总状态显示


        $ext_arr = json_decode($data['ext'], true);
        // 是拼团订单
        if (
            strpos($data['activity_type'], 'groupon') !== false &&
            isset($ext_arr['groupon_id']) && $ext_arr['groupon_id']
        ) {
            $groupon = ActivityGroupon::where('id', $ext_arr['groupon_id'])->find();
            if ($groupon) {
                if ($groupon['status'] == 'ing') {
                    // 尚未成团
                    $status_code = 'groupon_ing';
                } else if ($groupon['status'] == 'invalid') {
                    $status_code = 'groupon_invalid';
                }
            }
        }

        return $status_code;
    }




    /* -------------------------- 模型关联 ------------------------ */

    /**
     * 关联订单 item，只查 store_id 是当前门店的
     */
    public function item()
    {
        $store = Store::info();
        return $this->hasMany(\addons\shopro\model\OrderItem::class, 'order_id', 'id')->where('store_id', ($store ? $store['id'] : 0));
    }

    /**
     * 关联订单第一个 item，只查 store_id 是当前门店的
     */
    public function firstItem()
    {
        $store = Store::info();
        return $this->hasOne(\addons\shopro\model\OrderItem::class, 'order_id', 'id')->where('store_id', ($store ? $store['id'] : 0));
    }
    /* -------------------------- 模型关联 ------------------------ */
}
