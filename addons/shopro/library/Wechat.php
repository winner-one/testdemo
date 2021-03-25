<?php

namespace addons\shopro\library;

use EasyWeChat\Factory;
use addons\shopro\model\Config;
use think\Model;
use fast\Http;

/**
 *
 */
class Wechat extends Model
{
    protected $config;
    protected $app;


    public function __construct($platform)
    {
        $this->config = Config::getEasyWechatConfig($platform);
        switch ($platform) {
            case 'wxOfficialAccount':
                $this->app    = Factory::officialAccount($this->config);
                break;
            case 'wxMiniProgram':
                $this->app    = Factory::miniProgram($this->config);
                break;
        }

    }

    // 返回实例
    public function getApp() {
        return $this->app;
    }

    //小程序:获取openid&session_key
    public function code($code)
    {
        return $this->app->auth->session($code);
    }

    public function oauth()
    {
        $oauth = $this->app->oauth;
        return $oauth;
    }

    //解密信息
    public function decryptData($session, $iv, $encryptData)
    {
        $data = $this->app->encryptor->decryptData($session, $iv, $encryptData);

        return $data;
    }

    public function unify($orderBody)
    {
        $result = $this->app->order->unify($orderBody);
        return $result;
    }

    public function bridgeConfig($prepayId)
    {
        $jssdk = $this->app->jssdk;
        $config = $jssdk->bridgeConfig($prepayId, false);
        return $config;
    }

    public function notify()
    {
        $result = $this->app;
        return $result;
    }

    //获取accessToken
    public function getAccessToken()
    {
        $accessToken = $this->app->access_token;
        $token = $accessToken->getToken(); // token 数组  token['access_token'] 字符串
        //$token = $accessToken->getToken(true); // 强制重新从微信服务器获取 token.
        return $token;
    }

    public function sendTemplateMessage($attributes)
    {
        extract($attributes);
        $this->app->template_message->send([
            'touser' => $openId,
            'template_id' => $templateId,
            'page' => $page,
            'form_id' => $formId,
            'data' => $data,
            'emphasis_keyword' => $emphasis_keyword
        ]);
    }


    // 同步小程序直播
    public function live(Array $params = [])
    {
        $default = [
            'start' => 0,
            'limit' => 10
        ];
        $params = array_merge($default, $params);
        $default = json_encode($params);


        $access_token = $this->app->access_token->getToken();
        $getRoomsListUrl = "https://api.weixin.qq.com/wxa/business/getliveinfo?access_token={$access_token['access_token']}";
        $headers = ['Content-type: application/json'];
        $options = [
            CURLOPT_HTTPHEADER => $headers
        ];
        $result = Http::sendRequest($getRoomsListUrl, $default, 'POST', $options);
        if (isset($result['ret']) && $result['ret']) {
            $msg = json_decode($result['msg'], true);
            $result = $msg;
        }

//        $result = $this->app->live->getRooms(...array_values($params));

        $rooms = [];
        if ($result && $result['errcode'] == 0 && $result['errmsg'] === 'ok') {
            $rooms = $result['room_info'];
        }

        return $rooms;
    }

    // 小程序直播回放
    public function liveReplay(array $params = [])
    {
        $default = [
            'room_id' => 0,
            'start' => 0,
            'limit' => 20
        ];

        $params = array_merge($default, $params);
        $default = json_encode($params);
        $access_token = $this->app->access_token->getToken();
        $getPlayBackListUrl = "https://api.weixin.qq.com/wxa/business/getliveinfo?access_token={$access_token['access_token']}";
        $headers = ['Content-type: application/json'];
        $options = [
            CURLOPT_HTTPHEADER => $headers
        ];
        $result = Http::sendRequest($getPlayBackListUrl, $default, 'POST', $options);
        if (isset($result['ret']) && $result['ret']) {
            $msg = json_decode($result['msg'], true);
            $result = $msg;
        }
//        $result = $this->app->live->getPlaybacks(...array_values($params));

        $liveReplay = [];
        if ($result && $result['errcode'] == 0 && $result['errmsg'] === 'ok') {
            $liveReplay = $result['live_replay'];
        }

        return $liveReplay;
    }

    public function menu($act = 'create', $buttons = '')
    {
        $result = $this->app->menu->$act($buttons);
        return $result;

    }

    // 公众号 获取所有粉丝
    public function asyncFans($nextOpenId = null, $currentPage = 1, $totalPage = 1)
    {
        $fans = $this->app->user->list($nextOpenId);
        $openIdsArray = $fans['data']['openid'];
        //放入最大10000条openid队列去执行
        \think\Queue::push('\addons\shopro\job\Wechat@createQueueByOpenIdsArray', $openIdsArray, 'shopro');
        //第一次计算总页数
        if ($currentPage === 1) {
            $totalPage = intval($fans['total'] % $fans['count'] === 0 ? $fans['total'] / $fans['count'] : ceil($fans['total'] / $fans['count']));
        }
        //有分页 递归下一页
        if ($currentPage < $totalPage) {
            $openIdsArray = array_merge($openIdsArray, $this->asyncFans($fans['next_openid'], $currentPage++, $totalPage));
        }
        if ($currentPage == $totalPage) {
            if ($totalPage == 1) {
                $code = 1;
                $msg = '同步成功';
            }else{
                $code = 1;
                $msg = '数据较大,请稍后再查看...';
            }
            return [
                'code' => $code,
                'msg' => $msg
            ];
        }
        return $openIdsArray;
    }

    //通过openid获取已经关注的用户信息
    public function getSubscribeUserInfoByOpenId(array $openIdsArray)
    {
        $result = $this->app->user->select($openIdsArray);
        return $result;
    }




}
