<?php

namespace app\admin\controller\shopro;

use app\common\controller\Backend;
use think\Config;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            $datetimerange = explode(' - ', $this->request->request('datetimerange'));
            $startTime = strtotime($datetimerange[0]);
            $endTime = strtotime($datetimerange[1]);
            $this->model = new \app\admin\model\shopro\order\Order;
            $where = [
                'paytime' => ['between', [$startTime, $endTime]]
            ];

            $list = $this->model
                ->where($where)
                ->order('id')
                ->select();

            foreach ($list as $row) {
                $row->item = \app\admin\model\shopro\order\OrderItem::all(['order_id' => $row->id]);
            }
            $data['orderList'] = $list;

            // 商品列表
            $goodsList = \app\admin\model\shopro\goods\Goods::limit(5)->order('sales', 'desc')->select();
            foreach ($goodsList as $key => $goods) {
                $result = \app\admin\model\shopro\order\OrderItem::field('sum(goods_num * goods_price) as sale_total_money')->where('goods_id', $goods['id'])
                            ->whereExists(function ($query) use ($goods) {
                                $order_table_name = $this->model->getQuery()->getTable();
                                $table_name = (new \app\admin\model\shopro\order\OrderItem())->getQuery()->getTable();

                                $query->table($order_table_name)->where('order_id=' . $order_table_name . '.id')
                                    ->where('status', '>', \app\admin\model\shopro\order\Order::STATUS_NOPAY);       // 已支付的订单
                            })->find();

                $goods['sale_total_money'] = $result['sale_total_money'] ? : 0;
            }
            $data['goodsList'] = $goodsList;

            // 订单数量
            $data['orderNum'] = $this->model->where([
                'createtime' => ['between', [$startTime, $endTime]]
            ])->count('id');


            extract($this->orderScale($startTime, $endTime));

            $data['orderFinish'] = $orderFinish;
            $data['payedFinish'] = $payedFinish;

            $this->success('数据中心', '', $data);
        }

        return $this->view->fetch();
    }



    private function orderScale ($startTime, $endTime) {
        $where = [
            'createtime' => ['between', [$startTime, $endTime]]
        ];
        $list = $this->model
            ->where($where)
            ->order('id')
            ->select();

        $total = count($list);
        $total_money = array_sum(array_column($list, 'total_fee'));

        $data['orderFinish'] = [
            'order_scale' => 0,
            'order_user' => 0
        ];
        $data['payedFinish'] = [
            'payed_scale' => 0,
            'payed_money' => 0
        ];

        // 支付单数
        $payed_num = 0;
        // 支付金额
        $payed_money = 0;
        // 支付的用户 id
        $payed_user_ids = [];

        foreach ($list as $key => $order) {
            if ($order['status'] > 0) {
                $payed_num++;
                $payed_money += $order['total_fee'];
                $payed_user_ids[] = $order['user_id'];
            }
        }

        $orderFinish = [
            'order_scale' => $total ? round(($payed_num / $total), 2) : 0,
            'order_user' => count(array_unique($payed_user_ids)),
        ];

        $payedFinish = [
            'payed_scale' => $total_money ? round(($payed_money / $total_money), 2) : 0,
            'payed_money' => round($payed_money, 2)
        ];

        return compact("orderFinish", "payedFinish");
    }
}
