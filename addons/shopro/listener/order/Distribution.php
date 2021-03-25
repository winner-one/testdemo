<?php

namespace addons\shopro\listener\order;

// use addons\shopro\exception\Exception;
use addons\shopro\model\OrderItem;
use addons\shopro\model\User;
use addons\shopro\model\Order;
use think\Log;

/**
 * 订单确认收货
 */
class Distribution
{
    // 分销处理：
    public function orderFinish(&$params)
    {
        $order = $params['order'];
        $order = Order::where('id', $order['id'])->find();
        $OrderItem = OrderItem::where('order_id', $order['id'])->where('dispatch_status', OrderItem::DISPATCH_STATUS_GETED)->column('id');

        // // 独立远程日志配置
        // Log::init([
        //     'type'                => 'socket',
        //     'host'                => '120.77.244.152',
        //     //日志强制记录到配置的client_id
        //     'force_client_ids'    => ['zwy'],
        //     //限制允许读取日志的client_id
        //     'allow_client_ids'    => ['zwy'],
        //     // 日志记录级别
        //     'level' => [],
        // ]);
        // Log::write('分销处理：');
        // Log::write($params);
        foreach ($OrderItem as $key => $value) {
            $order_item = OrderItem::where('id', $value)->find();
            if ($order['referrer_id'] && $order_item['aftersale_status'] == '0') {
                \addons\shopro\model\User::moneyAdd($order['referrer_id'], $order_item->one_com, 'com', $order_item->id, []);
                $user = User::get($order['referrer_id']);
                $user->save(['frozen_money' => $user->frozen_money - $order_item->one_com]);
                if ($order['referrer_ids']) {
                    \addons\shopro\model\User::moneyAdd($order['referrer_ids'], $order_item->two_com, 'com', $order_item->id, []);
                    $user = User::get($order['referrer_ids']);
                    $user->save(['frozen_money' => $user->frozen_money - $order_item->one_com]);
                }
            }
        }
        return $params;
    }
}
