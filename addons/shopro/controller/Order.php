<?php

namespace addons\shopro\controller;

use think\Log;

class Order extends Base
{

    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];


    public function index()
    {
        $params = $this->request->get();

        $this->success('订单列表', \addons\shopro\model\Order::getList($params));
    }



    public function detail()
    {
        $params = $this->request->get();
        $this->success('订单详情', \addons\shopro\model\Order::detail($params));
    }


    public function itemDetail()
    {
        $params = $this->request->get();
        $this->success('订单商品', \addons\shopro\model\Order::itemDetail($params));
    }


    //
    public function statusNum()
    {
        $this->success('订单数量', \addons\shopro\model\Order::statusNum());
    }


    // 取消订单
    public function cancel()
    {
        $params = $this->request->post();

        // 表单验证
        $this->shoproValidate($params, get_class(), 'cancel');

        $this->success('取消成功', \addons\shopro\model\Order::operCancel($params));
    }

    // 删除订单
    public function delete()
    {
        $params = $this->request->post();

        // 表单验证
        $this->shoproValidate($params, get_class(), 'delete');

        $this->success('删除成功', \addons\shopro\model\Order::operDelete($params));
    }

    // 确认收货
    public function confirm()
    {
        $params = $this->request->post();

        // 表单验证
        $this->shoproValidate($params, get_class(), 'confirm');

        $this->success('收货成功', \addons\shopro\model\Order::operConfirm($params));
    }


    // 申请售后 （已废弃）
    // public function aftersale()
    // {
    //     $params = $this->request->post();

    //     // 表单验证
    //     $this->shoproValidate($params, get_class(), 'aftersale');

    //     $this->success('申请成功', \addons\shopro\model\Order::operAftersale($params));
    // }


    // 申请退款 (已废弃)
    // public function refund()
    // {
    //     $params = $this->request->post();

    //     // 表单验证
    //     $this->shoproValidate($params, get_class(), 'refund');

    //     $this->success('申请成功', \addons\shopro\model\Order::operRefund($params));
    // }


    public function comment()
    {
        $params = $this->request->post();

        // 表单验证
        $this->shoproValidate($params, get_class(), 'comment');

        $this->success('评价成功', \addons\shopro\model\Order::operComment($params));
    }


    public function getziti()
    {
        $params = $this->request->post();

        // 自提信息
        $days = [];
        if (!empty($params['day'])) {
            $arr['title'] = $params['day'] . ' ';
            $arr['value'] = $params['day'];
            array_push($days, $arr);
        } else {
            for ($i = 1; $i <= 5; $i++) {
                $times = date('Y-m-d', strtotime('+' . $i - 1 . ' days', time()));
                $arr['title'] = $times . ' ';
                $arr['value'] = $times;
                array_push($days, $arr);
            }
        }
        $obj = ['day' => $days, 'time' => ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'], 'richtext' => '6']; //到店自提服务协议富文本id

        $this->success('计算成功', $obj);
    }


    public function getBalance()
    {
        $params = $this->request->post();

        $store_id = 0;
        foreach ($params['confirmcartList'] as $key => $value) {
            if (!isset($params['store_id'])) {
                if (!$store_id) {
                    $store_id = $value['store_id'];
                } else {
                    if ($store_id != $value['store_id']) {
                        $this->error('不同商铺，商品不能一起结算，请分开结算', '');
                    }
                }
            }
        }
        $this->success('可以结算', '');
    }


    public function pre()
    {
        $params = $this->request->post();

        // 表单验证
        $this->shoproValidate($params, get_class(), 'pre');

        $result = \addons\shopro\model\Order::pre($params);
        Log::write($params);

        $this->success('计算成功', $result);
    }


    public function createOrder()
    {
        $params = $this->request->post();

        // 表单验证
        $this->shoproValidate($params, get_class(), 'createOrder');

        $order = \addons\shopro\model\Order::createOrder($params);

        $this->success('订单添加成功', $order);
    }


    // 获取可用优惠券列表
    public function coupons()
    {
        $params = $this->request->post();

        // 表单验证
        $this->shoproValidate($params, get_class(), 'coupons');

        $coupons = \addons\shopro\model\Order::coupons($params);

        $this->success('获取成功', $coupons);
    }
}
