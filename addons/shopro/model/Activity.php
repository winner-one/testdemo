<?php

namespace addons\shopro\model;

use think\Model;
use traits\model\SoftDelete;

/**
 * 活动模型
 */
class Activity extends Model
{
    use SoftDelete;

    // 表名,不含前缀
    protected $name = 'shopro_activity';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    protected $hidden = ['createtime', 'updatetime', 'deletetime', 'goods_ids'];
    //列表动态隐藏字段
//    protected static $listHidden = ['content', 'params', 'images', 'service_ids'];

    // 追加属性
    protected $append = [
    ];
    protected function getRulesAttr($value, $data)
    {
        $value = json_decode($value, true);
        return $value;
    }

    // public function dataRulesTemplate()
    // {
    //     $rules = [
    //         'limit_buy', 'once_buy', 'groupon_join_number', ''
            
    //     ];
    //     $table = [
    //         'activity_sku_price', 'activity_seckill', 'activity_groupon'
    //     ];
    //     $activity_sku_price = [
    //         'activity_id', 'goods_id', 'sku_price', 'sales', 'stock', 'price'
    //     ];
    // }


    public function activityGoodsSkuPrice() {
        return $this->hasMany(\addons\shopro\model\ActivityGoodsSkuPrice::class, 'activity_id', 'id');
    }
}
