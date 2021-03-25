<?php

namespace addons\shopro\controller;

/**
 * 拼团开的团
 *
 */

class ActivityGroupon extends Base
{
    protected $noNeedLogin = ['index'];
    protected $noNeedRight = ['*'];


    /**
     * 根据商品 id 获取正在拼的团
     */
    public function index() {
        $params = $this->request->get();

        $this->success('团列表', \addons\shopro\model\ActivityGroupon::getActivityGroupon($params));
    }


    public function detail () {
        $id = $this->request->get('id');

        $this->success('团详情', \addons\shopro\model\ActivityGroupon::getActivityGrouponDetail($id));
    }


    public function myGroupon () {
        $type = $this->request->get('type', 'all');
        $user = $this->auth->getUserInfo();

        $logs = \addons\shopro\model\ActivityGrouponLog::with(['groupon', 'order.firstItem', 'goods' => function ($query) {
            $query->removeOption('soft_delete');        // 商品查询包括被软删除的
        }]);
        
        if ($type != 'all') {
            $type = $type == 'finish' ? ['finish', 'finish-fictitious'] : [$type];
            $logs = $logs->whereExists(function ($query) use ($type) {
                $log_name = (new \addons\shopro\model\ActivityGrouponLog())->getQuery()->getTable();
                $groupon_name = (new \addons\shopro\model\ActivityGroupon())->getQuery()->getTable();
                $query->table($groupon_name)->where('id=' . $log_name . '.groupon_id')
                    ->where('status', 'in', $type);
            });
        }

        $logs = $logs->where('user_id', $user['id'])
                    ->order('id', 'desc')
                    ->paginate(10);

        // 将列表的显示价格，都查当时购买的价格，和当时对应的活动的 真实销量
        if ($grouponLogs = $logs->items()) {
            // 拿到所有活动 ids
            $activity_ids = array_column($grouponLogs, 'activity_id');

            // 一次获取所有活动，包括被软删除的活动，并关联规格
            $activities = \addons\shopro\model\Activity::withTrashed()
                                    ->with('activityGoodsSkuPrice')
                                    ->where('id', 'in', $activity_ids)->select();
            $activities = array_column($activities, null, 'id');

            foreach ($grouponLogs as $key => $grouponLog) {
                if (isset($grouponLog['goods']) && $grouponLog['goods'] && isset($activities[$grouponLog['activity_id']])) {
                    $activity = $activities[$grouponLog['activity_id']];
    
                    // 拿到当前商品对应的活动规格
                    $currentGoodsActivitySkuPrices = [];
                    foreach ($activity['activityGoodsSkuPrice'] as $k => $skuPrice) {
                        if ($skuPrice['status'] == 'up' && $skuPrice['goods_id'] == $grouponLog['goods_id']) {
                            $currentGoodsActivitySkuPrices[] = $skuPrice;
                        }
                    }

                    // 当时参加活动真实销量
                    if ($currentGoodsActivitySkuPrices) {
                        $grouponLogs[$key]['goods']['sales'] = array_sum(array_column($currentGoodsActivitySkuPrices, 'sales'));
                    }
                    // 这个是购买时候的活动单价
                    if (isset($grouponLog['order']['first_item'])) {
                        $grouponLogs[$key]['goods']['price'] = $grouponLog['order']['first_item']['goods_price'];
                    }
                }
            }

            $logs->data = $grouponLogs;
        }

        $this->success('我的拼团', $logs);
    }
}
