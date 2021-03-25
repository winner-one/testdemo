<?php

namespace addons\shopro\library\traits\model\order;

use addons\shopro\library\Wechat;
use addons\shopro\model\Order;
use addons\shopro\model\OrderItem;
use addons\shopro\model\Store;
use think\Cache;

trait OrderScope
{
    // 已失效
    public function scopeInvalid($query)
    {
        return $query->where('status', Order::STATUS_INVALID);
    }

    // 已取消
    public function scopeCancel($query)
    {
        return $query->where('status', Order::STATUS_CANCEL);
    }

    // 未支付
    public function scopeNopay($query)
    {
        return $query->where('status', Order::STATUS_NOPAY);
    }

    // 未发货
    public function scopeNosend($query)
    {
        $where = [
            'dispatch_status' => OrderItem::DISPATCH_STATUS_NOSEND,
            'refund_status' => ['not in', [OrderItem::REFUND_STATUS_OK, OrderItem::REFUND_STATUS_FINISH]]       // 没有退款完成
        ];

        return $query->whereExists(function ($query) use ($where) {
            $order_table_name = (new Order())->getQuery()->getTable();
            $table_name = (new OrderItem())->getQuery()->getTable();
            $query->table($table_name)->where('order_id=' . $order_table_name . '.id')->where($where);
        });
    }

    // 待收货
    public function scopeNoget($query)
    {
        $where = [
            'dispatch_status' => OrderItem::DISPATCH_STATUS_SENDED,
            'refund_status' => ['not in', [OrderItem::REFUND_STATUS_OK, OrderItem::REFUND_STATUS_FINISH]]       // 没有退款完成
        ];

        return $query->whereExists(function ($query) use ($where) {
            $order_table_name = (new Order())->getQuery()->getTable();
            $table_name = (new OrderItem())->getQuery()->getTable();
            $query->table($table_name)->where('order_id=' . $order_table_name . '.id')->where($where);
        });
    }


    // 待评价
    public function scopeNocomment($query)
    {
        $where = [
            'dispatch_status' => OrderItem::DISPATCH_STATUS_GETED,
            'refund_status' => ['not in', [OrderItem::REFUND_STATUS_OK, OrderItem::REFUND_STATUS_FINISH]],       // 没有退款完成
            'comment_status' => OrderItem::COMMENT_STATUS_NO
        ];

        return $query->whereExists(function ($query) use ($where) {
            $order_table_name = (new Order())->getQuery()->getTable();
            $table_name = (new OrderItem())->getQuery()->getTable();
            $query->table($table_name)->where('order_id=' . $order_table_name . '.id')->where($where);
        });
    }

    // 售后 (后台要用，虽然有专门的售后单列表)
    public function scopeAftersale($query)
    {
        return $query->whereExists(function ($query) {
            $order_table_name = (new Order())->getQuery()->getTable();
            $table_name = (new OrderItem())->getQuery()->getTable();
            $query->table($table_name)->where('order_id=' . $order_table_name . '.id')
                ->where('aftersale_status', '<>', OrderItem::AFTERSALE_STATUS_NOAFTER);
                // ->where(function($query) {
                //     // 只要申请过售后都算
                //     $query->where('aftersale_status', '<>', OrderItem::AFTERSALE_STATUS_NOAFTER)
                //         ->whereOr('refund_status', '<>', OrderItem::REFUND_STATUS_NOREFUND);
                // });
        });
    }

    // 退款 (即将废弃，有专门的售后单列表)
    public function scopeRefundStatus($query)
    {
        $where = [
            'refund_status' => ['<>', OrderItem::REFUND_STATUS_NOREFUND],       // 只要申请过退款
        ];

        return $query->whereExists(function ($query) use ($where) {
            $order_table_name = (new Order())->getQuery()->getTable();
            $table_name = (new OrderItem())->getQuery()->getTable();
            $query->table($table_name)->where('order_id=' . $order_table_name . '.id')->where($where);
        });
    }


    // 已支付
    public function scopePayed($query)
    {
        return $query->where('status', 'in', [Order::STATUS_PAYED, Order::STATUS_FINISH]);
    }

    // 已完成
    public function scopeFinish($query)
    {
        return $query->where('status', Order::STATUS_FINISH);
    }

    public function scopeCanAftersale($query)
    {
        return $query->where('status', 'in', [Order::STATUS_PAYED, Order::STATUS_FINISH]);
    }

    public function scopeCanDelete($query)
    {
        return $query->where('status', 'in', [
            Order::STATUS_CANCEL, 
            Order::STATUS_INVALID,
            Order::STATUS_FINISH
        ]);
    }



    // 门店订单 scope

    /**
     * 全部订单
     */
    public function scopeStore($query)
    {
        $store = Store::info();
        $where = [
            'store_id' => $store['id'],
        ];

        return $query->whereExists(function ($query) use ($where) {
            $order_table_name = (new Order())->getQuery()->getTable();
            $table_name = (new OrderItem())->getQuery()->getTable();
            $query->table($table_name)->where('order_id=' . $order_table_name . '.id')->where($where);
        });
    }


    /**
     * 未发货
     */
    public function scopeStoreNosend($query) {
        $store = Store::info();
        $where = [
            'store_id' => $store['id'],
            'dispatch_status' => OrderItem::DISPATCH_STATUS_NOSEND,
            'refund_status' => ['not in', [OrderItem::REFUND_STATUS_OK, OrderItem::REFUND_STATUS_FINISH]]       // 没有退款完成
        ];

        return $query->whereExists(function ($query) use ($where) {
            $order_table_name = (new Order())->getQuery()->getTable();
            $table_name = (new OrderItem())->getQuery()->getTable();
            $query->table($table_name)->where('order_id=' . $order_table_name . '.id')->where($where);
        });
    }


    /**
     * 未收货
     */
    public function scopeStoreNoget($query) {
        $store = Store::info();
        $where = [
            'store_id' => $store['id'],
            'dispatch_status' => OrderItem::DISPATCH_STATUS_SENDED,
            'refund_status' => ['not in', [OrderItem::REFUND_STATUS_OK, OrderItem::REFUND_STATUS_FINISH]]       // 没有退款完成
        ];

        return $query->whereExists(function ($query) use ($where) {
            $order_table_name = (new Order())->getQuery()->getTable();
            $table_name = (new OrderItem())->getQuery()->getTable();
            $query->table($table_name)->where('order_id=' . $order_table_name . '.id')->where($where);
        });
    }


    /**
     * 已完成
     */
    public function scopeStoreFinish($query) {
        $store = Store::info();
        // 收完货就算已完成
        $where = [
            'store_id' => $store['id'],
            'dispatch_status' => OrderItem::DISPATCH_STATUS_GETED,
            'refund_status' => ['not in', [OrderItem::REFUND_STATUS_OK, OrderItem::REFUND_STATUS_FINISH]]       // 没有退款完成
        ];

        return $query->whereExists(function ($query) use ($where) {
            $order_table_name = (new Order())->getQuery()->getTable();
            $table_name = (new OrderItem())->getQuery()->getTable();
            $query->table($table_name)->where('order_id=' . $order_table_name . '.id')->where($where);
        });
    }
}
