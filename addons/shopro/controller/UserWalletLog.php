<?php

namespace addons\shopro\controller;

use addons\shopro\exception\Exception;

class UserWalletLog extends Base
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];


    public function index()
    {
        $params = $this->request->get();
        $wallet_type = $params['wallet_type'] ?? 'money';
        $status = $params['status'] ?? 'all';

        if (!in_array($wallet_type, ['money', 'score'])) {
            throw new Exception('参数错误');
        }

        $this->success(($wallet_type == 'money' ? '钱包记录' : '积分记录'), \addons\shopro\model\UserWalletLog::getList($wallet_type, $status));
    }

}
