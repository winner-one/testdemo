<?php

namespace addons\shopro\controller;


class Decorate extends Base
{

    protected $noNeedLogin = ['saveImg'];
    protected $noNeedRight = ['*'];


    public function saveImg()
    {
        $params = $this->request->post();

        // 表单验证
        $this->shoproValidate($params, get_class(), 'saveImg');

        $id = $params['id'];
        $image = $params['image'];

        $result = \addons\shopro\model\Decorate::where('id', $id)->update(['image' => $image]);

        $this->success();
    }
}
