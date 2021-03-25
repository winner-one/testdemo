<?php

namespace addons\shopro\controller;

use addons\shopro\model\Share as ShareModel;

class Share extends Base
{

    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];

    public function add()
    {
        $params = $this->request->post();
        $params['platform'] = $this->request->header('platform');
        $this->success('添加分享记录', ShareModel::add($params));
    }


}