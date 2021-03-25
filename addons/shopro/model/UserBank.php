<?php

namespace addons\shopro\model;

use think\Model;
use addons\shopro\exception\Exception;
use think\Db;
use traits\model\SoftDelete;

/**
 * 钱包
 */
class UserBank extends Model
{
    use SoftDelete;

    // 表名,不含前缀
    protected $name = 'shopro_user_bank';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';


    // 追加属性
    protected $append = [
        
    ];


    // 银行卡详情
    public static function info()
    {
        $user = User::info();

        $bank = self::where(['user_id' => $user->id])->find();

        return $bank;
    }
    
    // 提现记录
    public static function edit($params) {
        $user = User::info();

        extract($params);

        $bank = self::where(['user_id' => $user->id])->find();

        if ($bank) {
            $bank->real_name = $real_name;
            $bank->bank_name = $bank_name;
            $bank->card_no = $card_no;
            $bank->save();
        } else {
            $bank = new self();
            $bank->user_id = $user->id;
            $bank->real_name = $real_name;
            $bank->bank_name = $bank_name;
            $bank->card_no = $card_no;
            $bank->save();
        }

        return $bank;
    }
}
