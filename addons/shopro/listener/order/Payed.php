<?php

namespace addons\shopro\listener\order;

use addons\shopro\exception\Exception;
use addons\shopro\model\Cart;
use addons\shopro\model\Config;
use addons\shopro\model\Order;
use addons\shopro\model\Store;
use addons\shopro\model\User;

/**
 * 支付成功
 */
class Payed
{

    // 订单支付成功
    public function orderPayedAfter(&$params)
    {
        // 订单支付成功
        $order = $params['order'];

        // 重新查询订单
        $order = Order::with('item')->where('id', $order['id'])->find();
        $items = $order ? $order['item'] : [];

        //分销配置信息
        $partner = json_decode(\addons\shopro\model\Config::where(['name' => 'partner'])->value('value'), true);

        // 有门店相关的订单
        $storeIds = [];
        foreach ($items as $item) {
            if (in_array($item['dispatch_type'], ['store', 'selfetch']) && $item['store_id']) {
                $storeIds[] = $item['store_id'];
            }
            if ($partner['partner_switch'] == '1' && $order['referrer_id']) {
                $money = (($item['goods_price'] * $item['goods_num']) - $item['discount_fee']);

                \addons\shopro\model\OrderItem::where('id', $item['id'])->update(['one_com' => ($money * $partner['one_com'])]);
                $user = User::get($order['referrer_id']);
                $user->save(['frozen_money' => $user->frozen_money + ($money * $partner['one_com'])]);
                // User::com(($money * $partner['one_com']), $order['referrer_id'], '订单号:' . $order['order_sn'], $item['id']);

                if ($partner['second_switch'] == '1' && $order['referrer_ids']) {

                    \addons\shopro\model\OrderItem::where('id', $item['id'])->update(['two_com' => ($money * $partner['two_com'])]);
                    $user = User::get($order['referrer_ids']);
                    $user->save(['frozen_money' => $user->frozen_money + ($money * $partner['two_com'])]);
                    // User::com(($money * $partner['two_com']), $order['referrer_ids'], '订单号:' . $order['order_sn'], $item['id']);

                }
            }
        }

        $data = [];
        if ($storeIds) {
            $data = [];
            // 存在门店，查询门店管理员
            $stores = Store::with(['userStore.user'])->where('id', 'in', $storeIds)->select();
            foreach ($stores as $key => $store) {
                $userStoreList = $store['user_store'];
                unset($store['user_store']);

                // 当前门店所有用户管理员
                $userList = [];
                foreach ($userStoreList as $user) {
                    if ($user['user']) {
                        $userList[] = $user['user'];
                    }
                }

                // 有用户才能发送消息
                if ($userList) {
                    $data[] = [
                        'store' => $store,
                        'userList' => $userList
                    ];
                }
            }
        }

        // 存在要通知的门店管理员
        if ($data) {
            // 按门店为单位发送通知
            foreach ($data as $key => $sendData) {
                \addons\shopro\library\notify\Notify::send(
                    $sendData['userList'],
                    new \addons\shopro\notifications\store\Order([
                        'store' => $store,
                        'order' => $order,
                        'event' => 'store_order_new'
                    ])
                );
            }
        }
    }
}
