<?php

namespace addons\shopro\model;

use think\Model;
use addons\shopro\exception\Exception;
use think\Db;
use traits\model\SoftDelete;

/**
 * 钱包
 */
class UserWalletApply extends Model
{
    use SoftDelete;

    // 表名,不含前缀
    protected $name = 'shopro_user_wallet_apply';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';


    // 追加属性
    protected $append = [
        'status_name',
        'status_text',
        'get_type_text'
    ];

    
    // 提现记录
    public static function getList() {
        $user = User::info();

        $walletApplys = self::where(['user_id' => $user->id])->select();

        return $walletApplys;
    }


    // 申请提现
    public static function apply ($params) {
        $user = User::info();

        extract($params);

        // 提现规则
        $config = Config::where('name', 'withdraw')->find();
        $config = json_decode($config['value'], true);

        $min = round(floatval($config['min']), 2);
        $max = round(floatval($config['max']), 2);
        $service_fee = round(floatval($config['service_fee']), 3);      // 三位小数

        if ($money <= 0) {
            throw new Exception('请输入提现金额');
        }

        if ($money < $min) {
            throw new Exception('提现金额不能小于 ' . $min);
        }

        if ($max && $money > $max) {
            throw new Exception('提现金额不能大于 ' . $max);
        }

        // 计算手续费
        $charge = $money * $service_fee;

        // 获取银行卡
        $bank = UserBank::where(['user_id' => $user->id])->find();
        if (!$bank) {
            throw new Exception('请先绑定银行卡');
        }

        $apply = Db::transaction(function () use ($user, $money, $charge, $bank, $service_fee) {
            $apply = new self();
            $apply->user_id = $user->id;
            $apply->money = $money;
            $apply->charge_money = $charge;
            $apply->service_fee = $service_fee;
            $apply->get_type = 'bank';
            $apply->bank_id = $bank->id;
            $apply->real_name = $bank->real_name;
            $apply->bank_info = json_encode([
                'bank_name' => $bank->bank_name, 
                'card_no' => $bank->card_no
            ]);
            $apply->status = 0;
            $apply->save();

            // 扣除余额
            User::moneyReduce($user->id, ($money + $charge), 'cash', $apply->id, [
                'money' => $money,
                'charge' => $charge,
                'bank_name' => $bank->bank_name,
                'card_no' => $bank->card_no
            ]);

            return $apply;
        });

        return $apply;
    }


    /**
     * 提现类型列表
     */
    public function getGetTypeList()
    {
        return ['bank' => '银行卡'];
    }


    /**
     * 提现类型中文
     */
    public function getGetTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['get_type']) ? $data['get_type'] : '');
        $list = $this->getGetTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    /**
     * 兼容后台 status_text
     */
    public function getStatusTextAttr($value, $data) {
        return $this->status_name;
    }


    public function getStatusNameAttr($value, $data) {
        switch($data['status']) {
            case 0: 
                $status_name = '申请处理中';
                break;
            case 1:
                $status_name = '提现成功';
                break;
            case -1:
                $status_name = '提现驳回';
                break;
            default:
                $status_name = '';
        }

        return $status_name;
    }

}
