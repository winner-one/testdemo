<?php

namespace addons\shopro\model;

use addons\shopro\exception\Exception;
use think\Model;

/**
 * 活动-拼团
 */
class ActivityGroupon extends Model
{
    // 表名,不含前缀
    protected $name = 'shopro_activity_groupon';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    protected $hidden = [];

    // 追加属性
    protected $append = [
    ];


    // 获取正在进行中的团
    public static function getActivityGroupon($params)
    {
        $goods_id = $params['goods_id'] ?? 0;
        $activity_id = $params['activity_id'] ?? 0;

        $activityGroupons = self::with('leader')
                            ->where('goods_id', $goods_id)
                            ->where('activity_id', $activity_id)
                            ->where('status', 'ing')
                            ->limit(10)
                            ->select();

        return $activityGroupons;
    }


    // 团详情
    public static function getActivityGrouponDetail ($id) {
        $activityGroupon = self::with(['my', 'grouponLog'])
                            ->where('id', $id)
                            ->find();

        if (!$activityGroupon) {
            throw new Exception('团未找到');
        }

        $detail = Goods::getGoodsDetail($activityGroupon['goods_id']);
        $activityGroupon['goods'] = $detail;

        // 如果没有关联出来活动，说明活动已结束，这时候处理 显示的活动价
        if ($activityGroupon['goods'] && !$activityGroupon['goods']['activity']) {
                // 活动已结束，或已删除，查询出来
            $activity = \addons\shopro\model\Activity::withTrashed()
                            ->with('activityGoodsSkuPrice')
                            ->where('id', $activityGroupon['activity_id'])->find();

            if ($activity) {
                $currentGoodsActivitySkuPrices = [];
                foreach ($activity['activityGoodsSkuPrice'] as $k => $skuPrice) {
                    if ($skuPrice['status'] == 'up' && $skuPrice['goods_id'] == $activityGroupon['goods_id']) {
                        $currentGoodsActivitySkuPrices[] = $skuPrice;
                    }
                }

                // 当时参加活动真实销量
                $activityGroupon['goods']['sales'] = array_sum(array_column($currentGoodsActivitySkuPrices, 'sales'));
                // 这个是活动最低价
                $activityGroupon['goods']['groupon_price'] = $activityGroupon['goods']['price'] = min(array_column($currentGoodsActivitySkuPrices, 'price'));
            }
        }

        return $activityGroupon;
    }



    public function activity() 
    {
        return $this->belongsTo(\addons\shopro\model\Activity::class, 'activity_id', 'id');
    }


    public function grouponLog()
    {
        return $this->hasMany(\addons\shopro\model\ActivityGrouponLog::class, 'groupon_id', 'id');
    }

    public function leader() {
        return $this->hasOne(\addons\shopro\model\ActivityGrouponLog::class, 'groupon_id', 'id')->where('is_leader', 1);
    }

    public function my() {
        $user = User::info();
        return $this->hasOne(\addons\shopro\model\ActivityGrouponLog::class, 'groupon_id', 'id')->where('user_id', ($user ? $user->id : 0));
    }

    public function goods()
    {
        return $this->belongsTo(\addons\shopro\model\Goods::class, 'goods_id', 'id');
    }
}
