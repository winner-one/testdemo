<?php

namespace addons\shopro\controller;

use addons\shopro\exception\Exception;
use addons\shopro\model\NotificationConfig;
use think\Cache;


class Notification extends Base
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];


    public function template()
    {
        // 获取小程序模板消息所有配置
        $notificationConfig = NotificationConfig::cache(300)->where('platform', 'wxMiniProgram')->select();

        $templates = [];
        foreach ($notificationConfig as $k => $config) {
            if ($config['status'] && $config['content_arr'] && $config['content_arr']['template_id']) {
                $templates[$config['event']] = $config['content_arr']['template_id'];
            }
        }

        $this->success('获取成功', $templates);
    }


}
