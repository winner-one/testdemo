<?php

namespace addons\shopro\listener\order;

use addons\shopro\exception\Exception;
use addons\shopro\model\ActivityGoodsSkuPrice;
use addons\shopro\model\Config;
use addons\shopro\model\Goods;
use addons\shopro\model\GoodsSkuPrice;
use addons\shopro\model\User;
use addons\shopro\model\UserCoupons;

/**
 * 订单退款
 */
class Refund
{
    // 订单同意退款前
    public function orderRefundBefore(&$params)
    {
        $order = $params['order'];
        $item = $params['item'];

    }


    // 订单同意退款后
    public function orderRefundAfter(&$params)
    {
        $order = $params['order'];
        $item = $params['item'];

        // 订单退款成功
        $user = User::where('id', $order['user_id'])->find();
        $user->notify(
            new \addons\shopro\notifications\Refund([
                'order' => $order,
                'item' => $item,
                'event' => 'refund_agree'
            ])
        );
    }


    // 订单退款拒绝前（钩子即将废弃 2020-07-28）
    public function orderRefundRefuseBefore(&$params)
    {
        $order = $params['order'];
        $item = $params['item'];
    }


    // 订单退款拒绝后（钩子即将废弃 2020-07-28）
    public function orderRefundRefuseAfter(&$params)
    {
        $order = $params['order'];
        $item = $params['item'];

        // 退款拒绝
        $user = User::where('id', $order['user_id'])->find();
        $user->notify(
            new \addons\shopro\notifications\Refund([
                'order' => $order,
                'item' => $item,
                'event' => 'refund_refuse'
            ])
        );
    }
}
