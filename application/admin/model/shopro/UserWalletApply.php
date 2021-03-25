<?php

namespace app\admin\model\shopro;

use think\Model;
use traits\model\SoftDelete;

class UserWalletApply extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'shopro_user_wallet_apply';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'get_type_text',
        'status_text'
    ];
    

    
    public function getGetTypeList()
    {
        return ['bank' => __('Bank')];
    }

    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1'), '-1' => __('Status -1')];
    }


    public function getGetTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['get_type']) ? $data['get_type'] : '');
        $list = $this->getGetTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
