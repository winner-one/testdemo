<?php

namespace addons\shopro\controller;

use think\Db;
use addons\shopro\model\User as UserModel;

class Partner extends Base
{

    protected $noNeedLogin = ['*'];
    // protected $noNeedLogin = ['lists', 'idsList', 'detail'];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $data['title'] = '我的团队';
        $data['userData'] = UserModel::where('id', $this->auth->id)->field(['id', 'jointime', 'username', 'nickname', 'mobile', 'avatar', 'score', 'birthday', 'money', 'frozen_money', 'group_id'])->find();
        $data['userData']['userconut'] = UserModel::where(['referrer_id' => $this->auth->id, 'status' => 'normal'])->count() + UserModel::where(['referrer_ids' => $this->auth->id, 'status' => 'normal'])->count();
        $data['userData']['couponconut'] = \addons\shopro\model\Order::where(['referrer_id' => $this->auth->id, 'status' => ['>', 0]])->count() + \addons\shopro\model\Order::where(['referrer_ids' => $this->auth->id, 'status' => ['>', 0]])->count();
        $this->success('获取用户数据', $data);
    }

    public function userlist()
    {
        $params = $this->request->get();

        $referrer_id = $this->request->get('level') ? "referrer_ids" : "referrer_id";

        $partner = json_decode(\addons\shopro\model\Config::where(['name' => 'partner'])->value('value'), true);
        $data['partner'] = $partner;
        if ($partner['partner_switch'] == '1') {
            $data['tabs'] = ['一级团队'];
            if ($partner['second_switch'] == '1') {
                array_push($data['tabs'], '二级团队');
            }
        }

        $data['data'] = UserModel::where($referrer_id, $this->auth->id)
            ->where('status', 'normal')
            ->field('id,nickname,username,referrer_id,avatar,jointime')
            ->order('id', 'desc')
            ->paginate(10, false, ['page' => $params['page']]);

        $count = UserModel::where($referrer_id, $this->auth->id)
            ->where('status', 'normal')
            ->field('id')
            ->count();
        $data['title'] = $data['tabs'][$this->request->get('level')] . '（' . $count . '人）';


        $this->success('获取用户数据', $data);
    }

    public function orderlist()
    {
        $params = $this->request->get();

        $partner = json_decode(\addons\shopro\model\Config::where(['name' => 'partner'])->value('value'), true);
        $data['partner'] = $partner;
        if ($partner['partner_switch'] == '1') {
            $data['tabs'] = ['一级团队订单'];
            if ($partner['second_switch'] == '1') {
                array_push($data['tabs'], '二级团队订单');
            }
        }

        $referrer_id = $this->request->get('level') ? "order.referrer_ids" : "order.referrer_id";
        $data['data'] = \addons\shopro\model\Order::alias('order')
            ->where([$referrer_id => $this->auth->id, 'order.status' => ['>', 0]])
            ->join('fa_shopro_order_item item', 'order.id = item.order_id', 'RIGHT')
            ->with('user')
            ->field('item.*, order.status, order.order_sn')
            ->order('id', 'desc')
            ->paginate(10, false, ['page' => $params['page']]);

        $referrer_id = $this->request->get('level') ? "referrer_ids" : "referrer_id";
        $count = \addons\shopro\model\Order::where([$referrer_id => $this->auth->id, 'status' => ['>', 0]])->count();
        $data['title'] = $data['tabs'][$this->request->get('level')] . '（' . $count . '单）';

        $this->success('获取用户数据', $data);
    }
}
