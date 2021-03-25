<?php

namespace addons\shopro\controller;


class UserBank extends Base
{

    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];


    public function info()
    {
        $this->success('银行卡', \addons\shopro\model\UserBank::info());
    }


    public function edit () {
        $params = $this->request->post();

        // 表单验证
        $this->shoproValidate($params, get_class(), 'edit');

        $this->success('编辑成功', \addons\shopro\model\UserBank::edit($params));
    }

}
