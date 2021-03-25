<?php

namespace addons\shopro\model;

use think\Model;
use think\Log;
use addons\shopro\exception\Exception;

/**
 * 购物车模型
 */
class Share extends Model
{

    // 表名,不含前缀
    protected $name = 'shopro_share';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    protected $hidden = ['createtime'];

    // 追加属性
    protected $append = [];


    public static function add($params)
    {

        Log::write('分享API');
        Log::write($params);
        $user = User::info();
        $url = $params['url'];
        if (!empty($url)) {
            $type = explode('-', $url);
        } else {
            $type = ['index', 0];
        }

        //分销配置信息
        $partner = json_decode(\addons\shopro\model\Config::where(['name' => 'partner'])->value('value'), true);
        $user = User::where('id', $user->id)->find();
        //判断是否有推荐人，判断是否开启分销功能，且推荐人id不为自己，判断用户是否为新用户（10分钟内注册的用户则算新用户）
        if (!$user['referrer_id'] && ($params['share_id'] != $user->id) && ((time() - $user->createtime) < 600) && $partner['partner_switch'] == '1') {
            if ($partner['partner_switch'] == '1') {
                User::where('id', $user->id)->update(['referrer_id' => $params['share_id']]);
                if ($partner['second_switch'] == '1') {
                    User::where('id', $user->id)->update(['referrer_ids' => User::where('id', $params['share_id'])->value('referrer_id')]);
                }
            }
        }

        self::create([
            'user_id' => $user->id,
            'share_id' => $params['share_id'],
            'type' => $type[0],
            'type_id' => $type[1],
            'platform' => $params['platform'],
            'createtime' => time(),
        ]);

        return true;
    }
}
