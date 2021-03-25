<?php

namespace app\admin\model\shopro\user;


use app\admin\model\shopro\user\MoneyLog;
use app\admin\model\shopro\user\ScoreLog;
use addons\shopro\library\notify\Notifiable;
use think\Model;
use think\Db;

class User extends Model
{
    use Notifiable;
    // 表名
    protected $name = 'user';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    // 追加属性
    protected $append = [
        // 'prevtime_text',
        // 'logintime_text',
        // 'jointime_text'
    ];

    public function getGenderList()
    {
        return ['1' => __('Male'), '0' => __('Female')];
    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }

    public function group()
    {
        return $this->belongsTo('Group', 'group_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    
    //后台钱包充值/扣除
    public static function walletChange($user, $type, $amount, $memo = '')
    {
        $result = false;
        //过滤冗余记录
        if($amount == 0) {
            return ['result' => $result, 'msg' => '请填写正确金额'];
        }
        //可扣除余额不足
        if($user->$type + $amount < 0) {
            return ['result' => $result, 'msg' => '可用余额不足'];
        }
        Db::startTrans();
        try {
            //shopro记录变动
            Db::name('shopro_user_wallet_log')->insert([
                'user_id' => $user->id,
                'wallet' => $amount,
                'type' => $amount > 0 ? 'admin_recharge' : 'admin_deduct',
                'wallet_type' => $type,
                'item_id' => 0,
                'oper_type' => 'admin',
                'oper_id' => \app\admin\library\Auth::instance()->id,
                'createtime' => time(),
                'updatetime' => time()
            ]);
            $current_wallet_log_id = Db::name('shopro_user_wallet_log')->getLastInsID();
            //Fa记录变动
            if($type === 'money') {
                $model = new \app\admin\model\shopro\user\MoneyLog;
            }elseif($type === 'score') {
                $model = new \app\admin\model\shopro\user\ScoreLog;
            }
            $model->save([
                'user_id' => $user->id,
                $type => $amount,
                'before' => $user->$type,
                'after' => $user->$type + $amount,
                'memo' => ($amount > 0 ? '后台充值' : '后台扣除') . ':' . $memo
            ]);
            $result = $user->setInc($type, $amount);

            // 钱包变动通知
            $currentWalletLog = \addons\shopro\model\UserWalletLog::where('id', $current_wallet_log_id)->find();
            $user->notify(
                new \addons\shopro\notifications\Wallet([
                    'walletLog' => $currentWalletLog,
                    'event' => $currentWalletLog['wallet_type'] == 'money' ? 'wallet_change' : 'score_change'
                ])
            );

            Db::commit();
        } catch (\think\exception\ValidateException $e) {
            Db::rollback();
            return ['result' => false, 'msg' => $e->getMessage()];
        } catch (\think\exception\PDOException $e) {
            Db::rollback();
            return ['result' => false, 'msg' => $e->getMessage()];
        }catch (\think\Exception $e) {
            Db::rollback();
            $result = false;
            return ['result' => false, 'msg' => $e->getMessage()];
        }
        if ($result !== false) {
            return ['result' => true, 'msg' => '充值成功'];
        }

    }
}
