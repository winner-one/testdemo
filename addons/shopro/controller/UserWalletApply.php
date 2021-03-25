<?php

namespace addons\shopro\controller;


class UserWalletApply extends Base
{

    protected $noNeedLogin = ['rule'];
    protected $noNeedRight = ['*'];


    public function index()
    {
        $this->success('提现记录', \addons\shopro\model\UserWalletApply::getList());
    }


    public function apply () {
        $params = $this->request->post();

        $this->success('申请成功', \addons\shopro\model\UserWalletApply::apply($params));
    }


    public function rule () {
        // 提现规则
        $config = \addons\shopro\model\Config::where('name', 'withdraw')->find();
        $config = json_decode($config['value'], true);

        $min = round(floatval($config['min']), 2);
        $max = round(floatval($config['max']), 2);
        $service_fee = floatval($config['service_fee']) * 100;
        $service_fee = round($service_fee, 1);      // 1 位小数

        $rule = '提现金额必须';
        if ($max) {
            $rule .= '在' . $min . '-' . $max . '元之间';
        } else {
            $rule .= '大于' . ($min >= 0 ? $min : 0);
        }

        if ($service_fee) {
            $rule .= '，提现手续费为提现金额的 '. $service_fee . '%';
        }

        $this->success('获取成功', $rule);
    }

}
