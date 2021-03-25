<?php

namespace addons\shopro\controller;

use app\common\library\Ems;
use app\common\library\Sms;
use fast\Random;
use think\Validate;
use addons\shopro\library\Wechat;
use think\Db;
use think\Log;
use addons\shopro\model\UserOauth;
use addons\shopro\model\User as UserModel;
use addons\shopro\model\UserStore;

/**
 * 会员接口
 */
class User extends Base
{
    protected $noNeedLogin = ['login', 'mobilelogin', 'accountLogin', 'register', 'resetpwd', 'changeemail', 'changemobile', 'third', 'wxMiniProgramLogin', 'getWxMiniProgramSessionKey', 'wxOfficialAccountLogin', 'wxOfficialAccountBaseLogin', 'wxOpenPlatformLogin', 'getUserDefaultFields'];
    protected $noNeedRight = '*';

    public function _initialize()
    {
        return parent::_initialize();
    }

    /**
     * 会员中心
     */
    public function index()
    {
        $auth = \app\common\library\Auth::instance();
        //储存登录信息
        $auth->setAllowFields(['id', 'username', 'nickname', 'mobile', 'avatar', 'score', 'birthday', 'referrer_id', 'referrer_ids', 'money', 'group', 'group_id']);
        $data = $auth->getUserinfo();
        if (!isset($data['group'])) {
            $data['group'] = \addons\shopro\model\UserGroup::get($data['group_id']);
        }

        // 查询用户优惠券数量
        $userCoupons = \addons\shopro\model\Coupons::getCouponsList(1);
        $data['coupons_num'] = count($userCoupons);

        // 查询用户是否是门店管理员
        $userStores = UserStore::where('user_id', $data['id'])->select();
        $data['is_store'] = $userStores ? 1 : 0;
        $data['store_id'] = 0;
        if (count($userStores) == 1) {
            // 只有一个店铺 直接进入店铺
            $data['store_id'] = $userStores[0]['store_id'];
        }

        $this->success('用户信息', $data);
    }

    /**
     * 会员登录
     *
     * @param string $account 账号
     * @param string $password 密码
     */
    public function accountLogin()
    {
        $account = $this->request->post('account');
        $password = $this->request->post('password');
        if (!$account || !$password) {
            $this->error(__('Invalid parameters'));
        }
        $ret = $this->auth->login($account, $password);
        if ($ret) {
            $data = ['userinfo' => $this->auth->getUserinfo()];
            $this->success(__('Logged in successful'), $data);
        } else {
            $this->error($this->auth->getError());
        }
    }
    /**
     * 获取微信小程序session_key
     *
     * @param string $code 加密code
     */
    public function getWxMiniProgramSessionKey()
    {
        $post = $this->request->post();
        $wechat = new Wechat('wxMiniProgram');
        $decryptSession = $wechat->code($post['code']);
        $this->success('获取session_key', $decryptSession);
    }
    /**
     * 微信小程序登录
     *
     * @param string $code 加密code
     */
    public function wxMiniProgramLogin()
    {
        $post = $this->request->post();
        $wechat = new Wechat('wxMiniProgram');
        $decryptUserInfo = $wechat->decryptData($post['session_key'], $post['iv'], $post['encryptedData']);
//        array(10) {
//        ["openId"] => string(28) "oD9ko40ZEoru7bjzN2f0Wnm3uNmA"
//        ["nickName"] => string(12) "Civilization"
//        ["gender"] => int(1)
//        ["language"] => string(5) "zh_CN"
//        ["city"] => string(0) ""
//        ["province"] => string(0) ""
//        ["country"] => string(7) "Iceland"
//        ["avatarUrl"] => string(124) "https://wx.qlogo.cn/mmopen/vi_32/bKchg38ErVBnuJBb4NfCOYkMsxgzZ3vZGu7y7V0BQG8clImsyvZge2HzGGYfxXKBNv8dbQOvyvmjKPEtRyrniaA/132"
//        ["unionId"] => string(28) "odsYQ1MLsGSGL6Gp5f6y3at2j7Hg"
//        ["watermark"] => array(2) {
//            ["timestamp"] => int(1590344429)
//            ["appid"] => string(18) "wx39cd0799d4567dd0"
//             }
//        }
        //组装decryptData
        $decryptData = array_change_key_case($decryptUserInfo, CASE_LOWER);
        $decryptData['headimgurl'] = $decryptData['avatarurl'];
        $decryptData['sex'] = $decryptData['gender'];
        $decryptData['session_key'] = $post['session_key'];
        if (empty($decryptData['openid'])) {
            Log::write(__('code错误'), $decryptData);
            $this->error(__('code错误'), $decryptData);
        }

        $ret = $this->oauthLoginOrRegister($decryptData, 'wxMiniProgram', 'Wechat');
        if ($ret) {
            $data = $ret->getUserinfo();

            if (!isset($data['group'])) {
                $data['group'] = \addons\shopro\model\UserGroup::get($data['group_id']);
            }
            $this->success(__('Logged in successful'), $data);
        }
        Log::write($this->auth->getError());
        $this->error($this->auth->getError());
    }

    /**
     * 微信APP登录
     *
     * @param string $authResult id信息
     * @param string $userInfo 用户信息
     */
    public function wxOpenPlatformLogin()
    {
        $params = $this->request->post();
        //App
//        'authResult' =>
//  array (
//      'access_token' => '32_WlLV5jxPoZuHWi7fnnxNPhun8Dj_igJPWOOxkP_WyEkHHc6pLpBz-13F3uu8KvS2HI0ei0l64Js8TD_W7A16JSr7PA16gwcDeU7RWd35gzQ',
//      'expires_in' => 7200,
//      'refresh_token' => '32_uvVpEiwt9q4ZmzDZgyF7jv8e4XxXValLOsuux4-GG7PIgywjnVL8Ovzzw-H1sKUd__X9NJko2lB49ZMo1ubYL4DT0Hv0C33yKkZDq8K0J9Y',
//      'openid' => 'oO01HuKtCFucLFr-Q9vwCLMGHO08',
//      'scope' => 'snsapi_userinfo',
//      'unionid' => 'odsYQ1IOJtHfVIK9KqPdy1U0uMBo',
//  ),
//  'userInfo' =>
//  array (
//      'openId' => 'oO01HuKtCFucLFr-Q9vwCLMGHO08',
//      'nickName' => 'RogerD',
//      'gender' => 1,
//      'city' => '中山',
//      'province' => '广东',
//      'country' => '中国',
//      'avatarUrl' => 'http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEK5uWViaPyje54F39j2FZYgvVicB0sQV1zLQ4ia41DYm7bqLlygCYu5sH8Y4MvfFWicNRUP4by7l1yhkg/132',
//      'unionId' => 'odsYQ1IOJtHfVIK9KqPdy1U0uMBo',
//  ),
        //组装decryptData
        $userInfo = array_change_key_case($params['userInfo'], CASE_LOWER);
        $userInfo['headimgurl'] = $userInfo['avatarurl'];
        $userInfo['sex'] = $userInfo['gender'];
        $decryptData = array_merge($params['authResult'], $userInfo);

        $ret = $this->oauthLoginOrRegister($decryptData, 'App', 'Wechat');
        if ($ret) {
            $data = $ret->getUserinfo();
            if (!isset($data['group'])) {
                $data['group'] = \addons\shopro\model\UserGroup::get($data['group_id']);
            }
            $this->success(__('Logged in successful'), $data);
        }

        $this->error($this->auth->getError());
    }


    /**
     * 微信公众号登录
     *
     * @param string $code 加密code
     */
    public function wxOfficialAccountLogin()
    {
        $wechat = new Wechat('wxOfficialAccount');
        $oauth = $wechat->oauth();
        //解析来源页面
        $oUrl = input('get.state');
        $url = explode('/', $oUrl);
        $procotol = $url[0] . '//';
        $host = $url[2];
        $decryptData = $oauth->user()->getOriginal();
//        ["openid"] => string(28) "oUgIVxASHEWU0wHd_ypNhJAdH088"
//        ["nickname"] => string(6) "東浩"
//        ["sex"] => int(1)
//        ["language"] => string(5) "zh_CN"
//        ["city"] => string(6) "郑州"
//        ["province"] => string(6) "河南"
//        ["country"] => string(6) "中国"
//        ["headimgurl"] => string(133) "http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEI3EldP4TQicjoF7utibMMRP08vfC9AOWia3WYmUWAGyI9GjWVkzMiahVYo2ef4KTNvVwLfhvKA3W0iccQ/132"
//        ["privilege"] => array(0) {
//        }
//        ["unionid"] => string(28) "odsYQ1CI3j4CK8_NmWdz0HuKsajY"

        $ret = $this->oauthLoginOrRegister($decryptData, 'wxOfficialAccount', 'Wechat');

        if ($ret) {
            //登录页接参Token
            header('Location:' . $procotol . $host . '/pages/public/login?token=' . $ret->getToken());
            exit;
        }
        $this->error($ret->getError());
    }

    /**
     * 微信公众号静默登录
     *
     * @param string $code 加密code
     */
    public function wxOfficialAccountBaseLogin()
    {
        $wechat = new Wechat('wxOfficialAccount');
        $oauth = $wechat->oauth();
        $oUrl = input('get.state');
        $url = explode('/', $oUrl);
        $decryptData = $oauth->user()->getOriginal();
        if ($decryptData) {
            header('Location:' . $oUrl . '&openid=' . $decryptData['openid']);
        } else {
            $this->error('未获取到OPENID');
        }


    }

    /**
     * 第三方登录或自动注册
     *
     * @param string $decryptData 解密参数
     * @param string $platform 平台名称
     * @param string $provider 厂商名称
     * @param string $keeptime 有效时长
     */
    private function oauthLoginOrRegister($decryptData, $platform, $provider, $keeptime = 0)
    {
        extract($decryptData);
        @$oauthData = compact('provider', 'unionid', 'platform', 'openid', 'nickname', 'sex', 'city', 'province', 'country', 'headimgurl', 'session_key', 'refresh_token', 'access_token');
        $oauthData['logintime'] = time();
        $oauthData['logincount'] = 1;
        if ($platform === 'wxMiniProgram' || $platform === 'App') {
            $oauthData['expire_in'] = 7200;
            $oauthData['expiretime'] = time() + 7200;
        }
        $auth = \app\common\library\Auth::instance();
        $auth->keeptime($keeptime);
        $auth->setAllowFields(['id', 'username', 'nickname', 'mobile', 'avatar', 'score', 'money', 'group', 'group_id']);

        $userOauth = UserOauth::get(['openid' => $openid]);
        if (isset($decryptData['nickname'])) {
            $fields['nickname'] = $decryptData['nickname'];
        }
        if (isset($decryptData['headimgurl'])) {
            $fields['avatar'] = $decryptData['headimgurl'];
        }
        //开启事务
        Db::startTrans();
        try {
            if ($userOauth) {
                //找到对应已注册用户,更新oauthData数据和用户数据直接发起登录
                $user_id = $userOauth->user_id;
                $user = UserModel::get($user_id);
                $oauthData['logincount'] = $userOauth->logincount + 1;
                $userOauth->save($oauthData);
            } else {
                //添加新的oauthData数据

                //默认创建新用户
                $createNewUser = true;
                // 判断是否有unionid 并且已存在oauth数据中
                if (isset($unionid)) {
                    //存在同厂商信息，添加oauthData数据，合并用户
                    $user_id = UserOauth::where(['unionid' => $unionid])->value('user_id');
                    $user = UserModel::get($user_id);
                    if ($user) {
                        $createNewUser = false;
                    }
                }
                if ($createNewUser) {
                    // 创建空用户
                    $username = Random::alnum(20);
                    $password = '';
                    $domain = request()->host();
                    $extend = $this->getUserDefaultFields();
                    $result = $auth->register($username, $password, $username . '@' . $domain, '', $extend, $keeptime);
                    if (!$result) {
                        return false;
                    }
                    $user = $auth->getUser();
                    if (!isset($fields['nickname'])) {
                        //默认昵称
                        $fields['nickname'] = $extend['nickname'] . $user->id;
                    }
                    // 更新会员资料
                    $user = UserModel::get($user->id);
                    // 保存第三方信息
                    $user_id = $user->id;
                }
                $oauthData['user_id'] = $user_id;
                UserOauth::create($oauthData);
            }
            if (isset($fields)) $user->save($fields);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $auth->logout();
            $this->error($e->getMessage());
            return false;
        }

        $auth->direct($user_id);

        return $auth;
    }

    /**
     * 手机验证码登录
     *
     * @param string $mobile 手机号
     * @param string $code 验证码
     */
    public function mobileLogin()
    {
        $mobile = $this->request->post('mobile');
        $code = $this->request->post('code');
        if (!$mobile || !$code) {
            $this->error(__('Invalid parameters'));
        }
        if (!Validate::regex($mobile, "^1\d{10}$")) {
            $this->error(__('Mobile is incorrect'));
        }
        if (!Sms::check($mobile, $code, 'mobilelogin')) {
            $this->error(__('Captcha is incorrect'));
        }
        $user = \app\common\model\User::getByMobile($mobile);
        if ($user) {
            if ($user->status != 'normal') {
                $this->error(__('Account is locked'));
            }
            //如果已经有账号则直接登录
            $ret = $this->auth->direct($user->id);
        }
        if ($ret) {
            Sms::flush($mobile, 'mobilelogin');
            $data = ['userinfo' => $this->auth->getUserinfo()];
            $this->success(__('Logged in successful'), $data);
        } else {
            $this->error($this->auth->getError());
        }
    }

    /**
     * 注册会员
     *
     * @param string $username 用户名
     * @param string $password 密码
     * @param string $email 邮箱
     * @param string $mobile 手机号
     * @param string $code 验证码
     */
    public function register()
    {
        $username = $this->request->post('mobile');
        $password = $this->request->post('password');
        $email = $this->request->post('mobile') . '@' . request()->host();

        $mobile = $this->request->post('mobile');
        $code = $this->request->post('code');
        if (!$password) {
            $this->error(__('请填写密码')); //TODO:密码规则校验
        }
        if (strlen($password) < 6 || strlen($password) > 16) {
            $this->error(__('密码长度 6-16 位')); //TODO:密码规则校验
        }
        if ($email && !Validate::is($email, "email")) {
            $this->error(__('邮箱填写错误'));
        }
        if ($mobile && !Validate::regex($mobile, "^1\d{10}$")) {
            $this->error(__('手机号填写错误'));
        }
        $ret = Sms::check($mobile, $code, 'register');
        if (!$ret) {
            $this->error(__('Captcha is incorrect'));
        }
        $extend = $this->getUserDefaultFields();
        $ret = $this->auth->register($username, $password, $email, $mobile, $extend);
        if ($ret) {
            $user = $this->auth->getUser();
            $user->nickname = $user->nickname . $user->id;
            $user->save();
            $data = ['userinfo' => $this->auth->getUserinfo()];
            $this->success(__('注册成功'), $data);
        } else {
            $this->error($this->auth->getError());
        }
    }

    /**
     * 注销登录
     */
    public function logout()
    {
        $this->auth->logout();
        $this->success(__('Logout successful'));
    }

    /**
     * 修改会员个人信息
     *
     * @param string $avatar 头像地址
     * @param string $username 用户名
     * @param string $nickname 昵称
     * @param string $birthday 生日
     * @param string $bio 个人简介
     */
    public function profile()
    {
        $user = $this->auth->getUser();
        $username = $this->request->post('username');
        $nickname = $this->request->post('nickname');
        $bio = $this->request->post('bio', '');
        $birthday = $this->request->post('birthday');
        $avatar = $this->request->post('avatar', '', 'trim,strip_tags,htmlspecialchars');
        if ($username) {
            $exists = \app\common\model\User::where('username', $username)->where('id', '<>', $this->auth->id)->find();
            if ($exists) {
                $this->error(__('Username already exists'));
            }
            $user->username = $username;
        }
        $user->nickname = $nickname;
        $user->bio = $bio;
        $user->birthday = $birthday;
        $user->avatar = $avatar;
        $user->save();
        $this->success();
    }

    /**
     * 修改邮箱
     *
     * @param string $email 邮箱
     * @param string $captcha 验证码
     */
    public function changeemail()
    {
        $user = $this->auth->getUser();
        $email = $this->request->post('email');
        $captcha = $this->request->request('captcha');
        if (!$email || !$captcha) {
            $this->error(__('Invalid parameters'));
        }
        if (!Validate::is($email, "email")) {
            $this->error(__('Email is incorrect'));
        }
        if (\app\common\model\User::where('email', $email)->where('id', '<>', $user->id)->find()) {
            $this->error(__('Email already exists'));
        }
        $result = Ems::check($email, $captcha, 'changeemail');
        if (!$result) {
            $this->error(__('Captcha is incorrect'));
        }
        $verification = $user->verification;
        $verification->email = 1;
        $user->verification = $verification;
        $user->email = $email;
        $user->save();

        Ems::flush($email, 'changeemail');
        $this->success();
    }

    /**
     * 修改手机号
     *
     * @param string $mobile 手机号
     * @param string $captcha 验证码
     */
    public function changemobile()
    {
        $user = $this->auth->getUser();
        $mobile = $this->request->post('mobile');
        $captcha = $this->request->post('captcha');
        if (!$mobile || !$captcha) {
            $this->error(__('Invalid parameters'));
        }
        if (!Validate::regex($mobile, "^1\d{10}$")) {
            $this->error(__('Mobile is incorrect'));
        }
        if (\app\common\model\User::where('mobile', $mobile)->where('id', '<>', $user->id)->find()) {
            $this->error(__('Mobile already exists'));
        }
        $result = Sms::check($mobile, $captcha, 'changemobile');
        if (!$result) {
            $this->error(__('Captcha is incorrect'));
        }
        $verification = $user->verification;
        $verification->mobile = 1;
        $user->verification = $verification;
        $user->mobile = $mobile;
        $user->save();

        Sms::flush($mobile, 'changemobile');
        $this->success();
    }


    /**
     * 重置密码
     *
     * @param string $mobile 手机号
     * @param string $newpassword 新密码
     * @param string $captcha 验证码
     */
    public function resetpwd()
    {
        $type = $this->request->post("type", 'mobile');
        $mobile = $this->request->post("mobile");
        $email = $this->request->post("email");
        $newpassword = $this->request->post("newpassword");
        $captcha = $this->request->post("captcha");
        if (!$newpassword || !$captcha) {
            $this->error(__('Invalid parameters'));
        }
        if (strlen($newpassword) < 6 || strlen($newpassword) > 16) {
            $this->error(__('密码长度 6-16 位')); //TODO:密码规则校验
        }
        if ($type == 'mobile') {
            if (!Validate::regex($mobile, "^1\d{10}$")) {
                $this->error(__('Mobile is incorrect'));
            }
            $user = \app\common\model\User::getByMobile($mobile);
            if (!$user) {
                $this->error(__('User not found'));
            }
            $ret = Sms::check($mobile, $captcha, 'resetpwd');
            if (!$ret) {
                $this->error(__('Captcha is incorrect'));
            }
            Sms::flush($mobile, 'resetpwd');
        } else {
            if (!Validate::is($email, "email")) {
                $this->error(__('Email is incorrect'));
            }
            $user = \app\common\model\User::getByEmail($email);
            if (!$user) {
                $this->error(__('User not found'));
            }
            $ret = Ems::check($email, $captcha, 'resetpwd');
            if (!$ret) {
                $this->error(__('Captcha is incorrect'));
            }
            Ems::flush($email, 'resetpwd');
        }
        //模拟一次登录
        $this->auth->direct($user->id);
        $ret = $this->auth->changepwd($newpassword, '', true);
        if ($ret) {
            $this->success(__('Reset password successful'));
        } else {
            $this->error($this->auth->getError());
        }
    }


    public function changepwd()
    {
        $user = $this->auth->getUser();

        $oldpassword = $this->request->post("oldpassword");
        $newpassword = $this->request->post("newpassword");

        if (!$newpassword || !$oldpassword) {
            $this->error(__('Invalid parameters'));
        }
        if (strlen($newpassword) < 6 || strlen($newpassword) > 16) {
            $this->error(__('密码长度 6-16 位')); //TODO:密码规则校验
        }

        $ret = $this->auth->changepwd($newpassword, $oldpassword);

        if ($ret) {
            $this->auth->direct($user->id);
            $data = ['userinfo' => $this->auth->getUserinfo()];

            $this->success(__('Change password successful'), $data);
        } else {
            $this->error($this->auth->getError());
        }
    }

    private function getUserDefaultFields()
    {
        $userConfig = json_decode(\addons\shopro\model\Config::get(['name' => 'user'])->value,true);
        return $userConfig;
    }
}
