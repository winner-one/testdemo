<?php

namespace app\admin\model\shopro\goods;

use think\Model;
use traits\model\SoftDelete;

class DaysPrice extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'shopro_goods_sku_days_price';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'status_text'
    ];

    
    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function shoprogoods()
    {
        return $this->belongsTo('app\admin\model\shopro\goods\Goods', 'goods_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function shoprogoodsskuprice()
    {
        return $this->belongsTo('app\admin\model\shopro\goods\SkuPrice', 'sku_price_ids', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
