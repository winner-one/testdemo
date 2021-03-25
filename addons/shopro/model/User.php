<?php

namespace addons\shopro\model;

use addons\shopro\exception\Exception;
use think\Model;
use app\common\library\Auth;
use think\Db;
use think\Log;
use app\common\model\MoneyLog;
use app\common\model\ScoreLog;
use app\common\model\ComLog;
use addons\shopro\library\notify\Notifiable;

/**
 * 会员模型
 */
class User extends Model
{
    use Notifiable;

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 追加属性
    protected $append = [
        'url',
        'jointime_text'
    ];

    public static function info()
    {
        if (Auth::instance()->id) {
            return Auth::instance();
        }
        return null;
    }

    public function getJointimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['jointime'] ?? 0;
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    /**
     * 获取个人URL
     * @param   string $value
     * @param   array  $data
     * @return string
     */
    public function getUrlAttr($value, $data)
    {
        return "/u/" . $data['id'];
    }

    /**
     * 获取头像
     * @param   string $value
     * @param   array  $data
     * @return string
     */
    public function getAvatarAttr($value, $data)
    {
        if (!$value) {
            //如果不需要启用首字母头像，请使用
            //$value = '/assets/img/avatar.png';
            $value = letter_avatar($data['nickname']);
        }

        return cdnurl($value, true);
    }

    /**
     * 获取会员的组别
     */
    public function getGroupAttr($value, $data)
    {
        return UserGroup::get($data['group_id']);
    }

    /**
     * 获取验证字段数组值
     * @param   string $value
     * @param   array  $data
     * @return  object
     */
    public function getVerificationAttr($value, $data)
    {
        $value = array_filter((array)json_decode($value, true));
        $value = array_merge(['email' => 0, 'mobile' => 0], $value);
        return (object)$value;
    }

    /**
     * 设置验证字段
     * @param mixed $value
     * @return string
     */
    public function setVerificationAttr($value)
    {
        $value = is_object($value) || is_array($value) ? json_encode($value) : $value;
        return $value;
    }

    /**
     * 变更会员佣金
     */
    public static function com($com, $user_id, $memo, $order_item_id)
    {
        $user = self::get($user_id);
        if ($user && $com != 0) {
            $before = $user->frozen_money;
            $after = $user->frozen_money + $com;
            //更新会员信息
            $user->save(['frozen_money' => $after]);
            //写入日志
            ComLog::create(['user_id' => $user_id, 'com' => $com, 'before' => $before, 'after' => $after, 'memo' => $memo, 'order_item_id' => $order_item_id]);
        }
    }

    /**
     * 变更会员余额
     * @param int    $money   余额
     * @param int    $user_id 会员ID
     * @param string $memo    备注
     */
    public static function money($money, $user_id, $memo, $frozen_money = 0)
    {
        $user = self::get($user_id);
        if ($user && $money != 0) {
            $before = $user->money;
            $after = $user->money + $money;
            //更新会员信息
            $data = ['money' => $after];
            if ($frozen_money != 0) {
                $afters = $user->frozen_money + $frozen_money;
                $data['frozen_money'] = $afters;
            }
            $user->save($data);
            //写入日志
            MoneyLog::create(['user_id' => $user_id, 'money' => $money, 'before' => $before, 'after' => $after, 'memo' => $memo]);
        }
    }

    /**
     * 变更会员积分
     * @param int    $score   积分
     * @param int    $user_id 会员ID
     * @param string $memo    备注
     */
    public static function score($score, $user_id, $memo)
    {
        $user = self::get($user_id);
        if ($user && $score != 0) {
            $before = $user->score;
            $after = $user->score + $score;
            $level = self::nextlevel($after);
            //更新会员信息
            $user->save(['score' => $after, 'level' => $level]);
            //写入日志
            ScoreLog::create(['user_id' => $user_id, 'score' => $score, 'before' => $before, 'after' => $after, 'memo' => $memo]);
        }
    }


    /**
     * 用户钱包增加
     * @return [type] [description]
     */
    public static function moneyAdd($user = null, $origin_money = 0, $type = '', $item_id = 0, $ext = [])
    {
        $money = sprintf("%.2f", $origin_money);     // 保留两位小数
        if (is_numeric($user)) {
            $user = User::where('id', $user)->find();
        }

        if (!is_null($user) && $money > 0) {
            $before = $user->money;

            $user->money = Db::raw('money + ' . $money);
            $user->save();      // 增加钱包余额

            // 记录 shopro UserWalletLog
            UserWalletLog::doAdd($user, $money, $type, $item_id, "money", 1, $ext);

            // 记录 fastadmin log
            \app\common\model\MoneyLog::create([
                'user_id' => $user->id,
                'money' => $money,
                'before' => $before,
                'after' => $before + $money,
                'memo' => UserWalletLog::getTypeName($type)
            ]);

            return $user;
        }

        Log::write($type . "--moneyAdd---增加失败 - 原始金额为：" . $origin_money .
            " - 保留两位小数:" . $money . " - 用户 id 为" . (is_null($user) ? 'no user' : $user->id));
        return $user;
    }


    public static function moneyReduce($user = null, $origin_money = 0, $type = '', $item_id = 0, $ext = [])
    {
        $money = sprintf("%.2f", $origin_money);     // 保留两位小数
        if (is_numeric($user)) {
            $user = User::where('id', $user)->find();
        }

        if (!is_null($user) && $money > 0) {
            $before = $user->money;
            if ($user->money < $money) {
                throw new Exception("余额不足");
            }

            $user->money = Db::raw('money - ' . $money);
            $user->save();      // 减少钱包余额

            // 记录 shopro UserWalletLog
            UserWalletLog::doAdd($user, $money, $type, $item_id, "money", 0, $ext);

            // 记录 fastadmin log
            \app\common\model\MoneyLog::create([
                'user_id' => $user->id,
                'money' => -$money,         // 减少为 - 数
                'before' => $before,
                'after' => $before - $money,
                'memo' => UserWalletLog::getTypeName($type)
            ]);

            return $user;
        }

        Log::write($type . "--moneyReduce---减少失败 - 原始金额为：" . $origin_money .
            " - 保留两位小数:" . $money . " - 用户 id 为" . (is_null($user) ? 'no user' : $user->id));
        return $user;
    }



    /**
     * 用户钱包增加 充值,购买优惠券返佣
     * @return [type] [description]
     */
    public static function scoreAdd($user = null, $origin_score = 0, $type = '', $item_id = 0, $ext = [])
    {
        $score = intval($origin_score);     // 整数
        if (is_numeric($user)) {
            $user = User::where('id', $user)->find();
        }

        if (!is_null($user) && $score > 0) {
            $before = $user->score;

            $user->score = Db::raw('score + ' . $score);
            $user->save();      // 增加积分

            // 记录 shopro UserWalletLog
            UserWalletLog::doAdd($user, $score, $type, $item_id, "score", 1, $ext);

            // 记录 fastadmin log
            \app\common\model\ScoreLog::create([
                'user_id' => $user->id,
                'score' => $score,
                'before' => $before,
                'after' => $before + $score,
                'memo' => UserWalletLog::getTypeName($type)
            ]);

            return $user;
        }

        Log::write($type . "--scoreAdd---增加失败 - 原始积分为：" . $origin_score .
            " - intval之后:" . $score . " - 用户 id 为" . (is_null($user) ? 'no user' : $user->id));
        return $user;
    }


    public static function scoreReduce($user = null, $origin_score = 0, $type = '', $item_id = 0, $ext = [])
    {
        $score = intval($origin_score);     // 整数
        if (is_numeric($user)) {
            $user = User::where('id', $user)->find();
        }

        if (!is_null($user) && $score > 0) {
            $before = $user->score;

            if ($user->score < $score) {
                throw new Exception("积分不足");
            }

            $user->score = Db::raw('score - ' . $score);

            $user->save();      // 增加钱包余额

            // 记录 shopro UserWalletLog
            UserWalletLog::doAdd($user, $score, $type, $item_id, "score", 0, $ext);

            // 记录 fastadmin log
            \app\common\model\ScoreLog::create([
                'user_id' => $user->id,
                'score' => -$score,
                'before' => $before,
                'after' => $before - $score,
                'memo' => UserWalletLog::getTypeName($type)
            ]);

            return $user;
        }

        Log::write($type . "--scoreReduce---减少失败 - 原始金额为：" . $origin_score .
            " - intval之后:" . $score . " - 用户 id 为" . (is_null($user) ? 'no user' : $user->id));
        return $user;
    }



    /**
     * 根据积分获取等级
     * @param int $score 积分
     * @return int
     */
    public static function nextlevel($score = 0)
    {
        $lv = array(1 => 0, 2 => 30, 3 => 100, 4 => 500, 5 => 1000, 6 => 2000, 7 => 3000, 8 => 5000, 9 => 8000, 10 => 10000);
        $level = 1;
        foreach ($lv as $key => $value) {
            if ($score >= $value) {
                $level = $key;
            }
        }
        return $level;
    }
}
