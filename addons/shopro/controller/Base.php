<?php

namespace addons\shopro\controller;

use addons\shopro\exception\Exception;
use app\common\controller\Api;
use think\Lang;
use think\Log;

class Base extends Api
{
    public function _initialize()
    {

        parent::_initialize();
        $controllername = strtolower($this->request->controller());
        $this->loadlang($controllername);
        $this->auth = \app\common\library\Auth::instance();

        // 独立远程日志配置
        Log::init([
            'type'                => 'socket',
            'host'                => '120.77.244.152',
            //日志强制记录到配置的client_id
            'force_client_ids'    => ['zwy'],
            //限制允许读取日志的client_id
            'allow_client_ids'    => ['zwy'],
            // 日志记录级别
            'level' => [],
        ]);
    }

    protected function loadlang($name)
    {
        Lang::load(ADDON_PATH  . 'shopro/lang/' . $this->request->langset() . '/' . str_replace('.', '/', $name) . '.php');
    }


    protected function shoproValidate($params, $class, $scene, $rules = [])
    {
        $validate = validate(str_replace('controller', 'validate', $class));
        if (!$validate->check($params, $rules, $scene)) {
            throw new Exception($validate->getError());
        }
    }
}
