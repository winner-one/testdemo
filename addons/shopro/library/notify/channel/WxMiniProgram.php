<?php

namespace addons\shopro\library\notify\channel;

use addons\shopro\notifications\Notification;

class WxMiniProgram
{

    public function __construct()
    {
    }


    /**
     * 发送 微信模板消息
     *
     * @param  mixed  $notifiable       // 通知用户
     * @param  通知内容
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $data = [];

        if (method_exists($notification, 'toWxMiniProgram')) {
            $data = $notification->toWxMiniProgram($notifiable);

            if ($data && isset($data['openid']) && isset($data['template_id'])) {
                $data['touser'] = $data['openid'];
                unset($data['openid']);

                // 发送模板消息
                $result = (new \addons\shopro\library\Wechat('wxMiniProgram'))->getApp()->subscribe_message->send($data);

                if ($result['errcode'] != 0) {
                    // 短信发送失败
                    \think\Log::write('小程序模板消息发送失败：用户：'. $notifiable['id'] . '；类型：' . get_class($notification) . "；发送类型：" . $notification->event . "；错误信息：" . json_encode($result));
                } else {
                    // 发送成功
                    $notification->sendOk('wxMiniProgram');
                }

                return true;
            }

            // 没有openid
            \think\Log::write('小程序模板消息发送失败，没有 openid：用户：' . $notifiable['id'] . '；类型：' . get_class($notification) . "；发送类型：" . $notification->event);
        }

        return true;
    }
}
