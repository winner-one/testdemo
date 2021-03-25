<?php

namespace addons\shopro\model;

use think\Model;
use addons\shopro\exception\Exception;
use addons\shopro\library\traits\ActivityCache;
use addons\shopro\model\GoodsSku;
use addons\shopro\model\GoodsSkuPrice;
use think\Db;
use traits\model\SoftDelete;

/**
 * 商品模型
 */
class Goods extends Model
{
    use SoftDelete, ActivityCache;

    // 表名,不含前缀
    protected $name = 'shopro_goods';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    protected $hidden = ['createtime', 'updatetime', 'status'];
    //列表动态隐藏字段
    protected static $list_hidden = ['content', 'params', 'images', 'service_ids'];

    // 追加属性
    protected $append = [
        'dispatch_type_arr'
    ];


    /**
     * 获取指定日期范围最小库存
     */
    public static function getOrderStock($id, $day, $days, $skuId)
    {
        $detail = Db::name('shopro_goods')->where('id', $id)->find();
        $times = date('Y-m-d', strtotime('+' . $detail['days'] - 1 . ' days', time()));

        // 判断是否超出预约范围
        if (strtotime($days[count($days) - 1]) > strtotime($times)) {
            throw new Exception("超出预约范围");
        }

        $where = ['goods_id' => $id, 'deletetime' => null];
        $wheres = ['goods_id' => $id, 'deletetime' => null];
        if ($skuId != '0') {                //可以优化，将sku带入查询，goods_sku_ids=null
            $where['goods_sku_ids'] = $skuId;
            $sku_price_id = Db::name('shopro_goods_sku_price')->where($where)->value('id');
            $wheres['sku_price_ids'] = $sku_price_id;
        }
        $good_stock = Db::name('shopro_goods_sku_price')->where($where)->value('stock');         //获取产品平时日期的库存

        $SmallStock = 0;
        $one = true;
        if ($skuId != '0') {
        }
        foreach ($days as $key => $day) {       //判断日期范围内库存是否充足
            $info = Db::name('shopro_goods_sku_days_price')->where('day', $day)->where($wheres)->find();

            if ($info && $info['status'] != '1') {  //判断当天是否停止预约
                throw new Exception("日期：" . $day . "停售");
            } else {
                $day_stock = self::getDayStock($id, $day, $skuId != '0' ? $sku_price_id : $skuId);
                $stock = !$info ? $good_stock - $day_stock : $info['stock'] - $day_stock;   //判断是否有指定当天日期库存,然后减去当天已售库存
                // echo $day;
                // echo $stock;
                if ($stock < 1) {
                    throw new Exception("日期：" . $day . "库存不足");
                }
            }
            if ($one) {
                $SmallStock = $stock;
                $one = false;
            } else {    //获取最小日期的库存
                $SmallStock = $SmallStock > $stock ? $stock : $SmallStock;
            }
        }
        return $SmallStock;
    }


    /**
     * 获取指定日期库存
     */
    public static function getDayStock($id, $day, $sku_price_id)
    {
        $wheres = ['item.goods_id' => $id];
        if ($sku_price_id) {        //判断产品的哪个规格库存
            $wheres['item.goods_sku_price_id'] = $sku_price_id;
        }
        $day_stock = Db::name('shopro_order')
            ->alias('order')
            ->join("shopro_order_item item", 'order.id=item.order_id', 'LEFT')
            ->where('item.day', $day)
            ->where('item.aftersale_status', '<>', '2')
            ->where('item.refund_status', '<>', '2')
            ->where($wheres)
            ->where('order.status', 'in', [0, 1, 2])
            ->sum('item.goods_num');

        return $day_stock;
    }


    /**
     * 获取指定商品已已预约的情况
     */
    public static function dashboard($id)
    {
        $goods = Db::name('shopro_goods')->where('id', $id)->find();
        $sku_price_ids = Db::name('shopro_goods_sku_price')->where('goods_id', $id)->column('id');

        $i = 0;
        foreach ($sku_price_ids as $key => $sku_price_id) {
            $where = ['goods_id' => $id, 'deletetime' => null, 'id' => $sku_price_id];
            $info = Db::name('shopro_goods_sku_price')->where($where)->field('stock, goods_sku_ids')->find();         //获取产品平时日期的库存
            $days = [];
            for ($i = 1; $i <= $goods['days']; $i++) {
                $arr['day'] = date('Y-m-d', strtotime('+' . $i - 1 . ' days', time()));
                $sku_days_price = Db::name('shopro_goods_sku_days_price')->where('day', $arr['day'])->where('sku_price_ids', $sku_price_id)->where('goods_id', $id)->find();
                if ($sku_days_price && $sku_days_price['status'] != '1') {  //判断当天是否停止预约
                    $arr['stock'] = '停售';   //判断是否有指定当天日期库存,然后减去当天已售库存
                    $arr['day_stock'] = '停售';
                    $arr['stock_all'] = '停售';
                } else {
                    $day_stock = self::getDayStock($id, $arr['day'], $sku_price_id);
                    $arr['stock'] = !$info ? $sku_days_price['stock'] - $day_stock : $info['stock'] - $day_stock;   //判断是否有指定当天日期库存,然后减去当天已售库存
                    $arr['day_stock'] = $day_stock;
                    $arr['stock_all'] = !$info ? $sku_days_price['stock'] : $info['stock'];
                }
                array_push($days, $arr);
            }
            $data[Db::name('shopro_goods_sku')->where('id', $info['goods_sku_ids'])->value('name')] = $days;
        }
        // var_dump($data);
        return $data;
    }


    /**
     * params 请求参数
     * is_page 是否分页
     */
    public static function getGoodsList($params, $is_page = true)
    {
        extract($params);
        $where = [
            'status' => 'up',
        ];
        //排序字段
        if (isset($order)) {
            $order = self::getGoodsListOrder($order);
        } else {
            $order = 'weigh desc';
        }
        if (isset($keywords) && $keywords !== '') {
            $where['title|subtitle'] = ['like', "%$keywords%"];
        }

        if (isset($goods_ids) && $goods_ids !== '') {
            $goodsIdsArray = explode(',', $goods_ids);
            $where['id'] = ['in', $goodsIdsArray];
        }

        $category_ids = [];
        if (isset($category_id) && $category_id != 0) {
            // 查询分类所有子分类,包括自己
            $category_ids = Category::getCategoryIds($category_id);
        }

        $goods = self::where($where)->where(function ($query) use ($category_ids) {
            // 所有子分类使用 find_in_set or 匹配，亲测速度并不慢
            foreach ($category_ids as $key => $category_id) {
                $query->whereOrRaw("find_in_set($category_id, category_ids)");
            }
        });

        // 过滤有活动的商品
        if (isset($no_activity) && $no_activity) {
            $goods = $goods->whereNotExists(function ($query) use ($where) {
                $activityTableName = (new Activity())->getQuery()->getTable();
                $goodsTableName = (new self())->getQuery()->getTable();
                $query->table($activityTableName)->where("find_in_set(" . $goodsTableName . ".id, goods_ids)")->where('deletetime', 'null');        // 必须手动加上 deletetime = null
            });
        }

        $goods = $goods->orderRaw($order);

        $cacheKey = 'goodslist-' . ($is_page ? 'page' : 'all') . '-' . md5(json_encode($params));

        // 判断缓存
        $goodsCache = cache($cacheKey);
        if ($goodsCache) {
            // 存在缓存直接 返回
            $goodsCache = json_decode($goodsCache, true);
            return $goodsCache ?: [];
        }

        if ($is_page) {
            $goods = $goods->paginate($per_page ?? 10);
            $goodsData = $goods->items();
        } else {
            $goods = $goodsData = $goods->select();
        }

        $data = [];
        if ($goodsData) {
            $collection = collection($goodsData);
            $data = $collection->hidden(self::$list_hidden);

            // 处理活动
            // load_relation($data, 'skuPrice');        // 只针对数组
            $data->load('skuPrice');        // 延迟预加载

            // if (!isset($no_activity) || !$no_activity) {        // 没有 传入 no_activity 或者 no_activity = false
            // 默认查询活动， no_activity 的时候这里也要执行一下，这里计算了销量规格等信息
            foreach ($data as $key => $g) {
                $data[$key] = self::operActivitySkuPrice($g, $g['sku_price']);
            }
            // }
        }

        if ($is_page) {
            $goods->data = $data;
        } else {
            $goods = $data;

            // 目前只缓存不分页的请求
            cache($cacheKey, json_encode($goods), (600 + mt_rand(0, 300)));
        }

        return $goods;
    }

    public static function getGoodsListByIds($goodsIds)
    {
        $goodsIdsArray = explode(',', $goodsIds);
        $where = [
            'status' => 'up',
            'deletetime' => null,
            'id' => ['in', $goodsIdsArray]
        ];
        $goods = self::where($where)->paginate(10);

        if ($goods->items()) {
            $collection = collection($goods->items());
            $data = $collection->hidden(self::$list_hidden);

            // 处理活动
            // load_relation($data, 'skuPrice');        // 只针对数组
            $data->load('skuPrice');        // 延迟预加载
            foreach ($data as $key => $g) {
                $data[$key] = self::operActivitySkuPrice($g, $g['sku_price']);
            }

            $goods->data = $data;
        }
        return $goods;
    }

    public static function getFavoriteGoodsList($type = 'normal', $status = 'up')
    {
        $where = [
            'type' => $type,
            'status' => $status,
            'deletetime' => null,
        ];

        $goods = self::where($where)->paginate(10);

        if ($goods->items()) {
            $collection = collection($goods->items());
            $data = $collection->hidden(self::$list_hidden);
            $goods->data = $data;
        }
        return $goods;
    }


    // 获取秒杀商品列表
    public static function getSeckillGoodsList($params)
    {
        extract($params);
        $type = $type ?? 'all';

        if ((new self)->hasRedis()) {
            // 如果有redis，读取 redis
            $activityList = (new self)->getActivityList('seckill', $type);
        } else {
            $where = [
                'type' => 'seckill'
            ];
            if ($type == 'ing') {
                $where['starttime'] = ['<', time()];
                $where['endtime'] = ['>', time()];
            } else if ($type == 'nostart') {
                $where['starttime'] = ['>', time()];
            } else if ($type == 'ended') {
                $where['endtime'] = ['<', time()];
            }

            $activityList = Activity::where($where)->select();
        }

        // 获取所有商品 id
        $goodsIds = '';
        foreach ($activityList as $key => $activity) {
            $goodsIds .= ',' . $activity['goods_ids'];
        }

        if ($goodsIds) {
            $goodsIds = trim($goodsIds, ',');
        }

        $goodsList = self::getGoodsListByIds($goodsIds);

        return $goodsList;
    }


    // 获取拼团商品列表
    public static function getGrouponGoodsList($params)
    {
        extract($params);
        $type = 'ing';

        if ((new self)->hasRedis()) {
            // 如果有redis，读取 redis
            $activityList = (new self)->getActivityList('groupon', $type);
        } else {
            $where = [
                'type' => 'groupon'
            ];
            if ($type == 'ing') {
                $where['starttime'] = ['<', time()];
                $where['endtime'] = ['>', time()];
            }

            $activityList = Activity::where($where)->select();
        }

        // 获取所有商品 id
        $goodsIds = '';
        foreach ($activityList as $key => $activity) {
            $goodsIds .= ',' . $activity['goods_ids'];
        }

        if ($goodsIds) {
            $goodsIds = trim($goodsIds, ',');
        }

        $goodsList = self::getGoodsListByIds($goodsIds);

        return $goodsList;
    }



    public static function getGoodsDetail($id)
    {
        $user = User::info();

        $detail = (new self)->where('id', $id)->with(['favorite' => function ($query) use ($user) {
            $user_id = empty($user) ? 0 : $user->id;
            return $query->where(['user_id' => $user_id]);
        }, 'store'])->find();

        if (!$detail || $detail->status === 'down') {
            throw new Exception('商品不存在或已下架');
        }

        $detail = $detail->append(['service', 'sku', 'coupons']);

        // 处理活动规格
        $detail = self::operActivitySkuPrice($detail, $detail->sku_price);

        return $detail;
    }


    /**
     * 获取自提点
     */
    public static function getGoodsStore($params)
    {
        $user = User::info();

        $id = $params['id'] ?? 0;
        $latitude = $params['latitude'] ?? 0;
        $longitude = $params['longitude'] ?? 0;

        $detail = (new self)->where('id', $id)->find();

        $selfetch = [];
        if ($detail && strpos($detail['dispatch_type'], 'selfetch') !== false) {
            // 商品支持自提，查询自提模板
            $dispatch = Dispatch::where('type', 'selfetch')->where('id', 'in', $detail['dispatch_ids'])->find();
            if ($dispatch) {
                // 查询自提点模板
                $dispatchSelfetch = DispatchSelfetch::where('id', 'in', $dispatch['type_ids'])
                    ->order('id', 'asc')->find();

                if ($dispatchSelfetch) {
                    // 查询自提点
                    $selfetch = Store::where('selfetch', 1)->where('id', 'in', $dispatchSelfetch['store_ids']);
                    if ($latitude && $longitude) {
                        $selfetch = $selfetch->field('*, ' . getDistanceBuilder($latitude, $longitude))->order('distance', 'asc');
                    }

                    $selfetch = $selfetch->select();
                }
            }
        }

        return $selfetch;
    }


    // 处理活动规格
    public static function operActivitySkuPrice($detail, $sku_price)
    {
        $activity = (new self)->getActivity($detail['id']);

        if (!empty($activity)) {
            switch ($activity['type']) {
                case 'seckill':
                    $activity_goods_sku_price = $activity['activity_goods_sku_price'];
                    $new_sku_price = [];
                    foreach ($sku_price as $s => $k) {
                        $new_sku_price[$s] = $k;
                        $new_sku_price[$s]['stock'] = 0;
                        $new_sku_price[$s]['sales'] = 0;
                        foreach ($activity_goods_sku_price as $c) {
                            if ($k['id'] == $c['sku_price_id']) {
                                // 采用活动的 规格内容
                                $new_sku_price[$s]['stock'] = $c['stock'];
                                $new_sku_price[$s]['sales'] = $c['sales'];
                                $new_sku_price[$s]['price'] = $c['price'];
                                $new_sku_price[$s]['status'] = $c['status'];        // 采用活动的上下架

                                // 记录相关活动类型
                                $new_sku_price[$s]['activity_type'] = $activity['type'];
                                $new_sku_price[$s]['activity_id'] = $activity['id'];
                                // 记录对应活动的规格的记录
                                $new_sku_price[$s]['item_goods_sku_price'] = $c;
                                break;
                            }
                        }
                    }

                    $sku_price = $new_sku_price;
                    break;
                case 'groupon':
                    $activity_goods_sku_price = $activity['activity_goods_sku_price'];
                    $new_sku_price = [];
                    foreach ($sku_price as $s => $k) {
                        $new_sku_price[$s] = $k;
                        $new_sku_price[$s]['stock'] = 0;
                        $new_sku_price[$s]['sales'] = 0;
                        foreach ($activity_goods_sku_price as $c) {
                            if ($k['id'] == $c['sku_price_id']) {
                                // 采用活动的 规格内容
                                $new_sku_price[$s]['stock'] = $c['stock'];
                                $new_sku_price[$s]['sales'] = $c['sales'];
                                $new_sku_price[$s]['groupon_price'] = $c['price'];      // 不覆盖原来规格价格，用作单独购买，讲活动的价格设置为新的拼团价格
                                $new_sku_price[$s]['status'] = $c['status'];        // 采用活动的上下架

                                // 记录相关活动类型
                                $new_sku_price[$s]['activity_type'] = $activity['type'];
                                $new_sku_price[$s]['activity_id'] = $activity['id'];
                                // 记录对应活动的规格的记录（不要了，减小响应包体积, 还得要，下单的时候需要存活动 的 sku_id）
                                $new_sku_price[$s]['item_goods_sku_price'] = $c;
                                break;
                            }
                        }
                    }

                    $sku_price = $new_sku_price;
                    break;
            }

            // 减小响应包体积
            unset($activity['activity_goods_sku_price']);
        }

        // 商品参与的活动
        // 所有的都需要设置一下， 要不然找不到类的属性，如果不存在活动，则都是 null
        $detail->activity = $activity ?: null;
        $detail->activity_type = $activity['type'] ?? null;

        // 移除下架的规格
        foreach ($sku_price as $key => $sku) {
            if ($sku['status'] != 'up') {
                unset($sku_price[$key]);
            }
        }

        if ($activity) {
            $prices = array_column($sku_price, 'price');
            $detail['price'] = $prices ? min($prices) : 0;      // min 里面不能是空数组

            if ($activity['type'] == 'groupon') {
                $grouponPrices = array_column($sku_price, 'groupon_price');
                $detail['groupon_price'] = $grouponPrices ? min($grouponPrices) : 0;
            }

            $detail['sales'] = array_sum(array_column($sku_price, 'sales'));
        } else {
            // 正常商品加上显示销量
            $detail['sales'] += $detail['show_sales'];
        }

        $detail['sku_price'] = array_values($sku_price);
        $detail['stock'] = array_sum(array_column($sku_price, 'stock'));





        return $detail;
    }


    public function getActivity($goods_id)
    {
        if ($this->hasRedis()) {
            // 如果有活动，读取 redis
            $activity = $this->getGoodsActivity($goods_id);
            return $activity;
        }

        // 没有配置 redis
        $activity = Activity::where('find_in_set(:id,goods_ids)', ['id' => $goods_id])
            ->with(['activityGoodsSkuPrice' => function ($query) use ($goods_id) {
                $query->where('goods_id', $goods_id)
                    ->where('status', 'up');
            }])
            ->where([
                'deletetime' => null,
            ])->find();

        return $activity;
    }


    public function getCouponsAttr($value, $data)
    {
        $goods_id = $data['id'];

        $coupons = Coupons::where(function ($query) use ($goods_id) {
            $query->where('find_in_set(' . $goods_id . ',goods_ids)')
                ->whereOr('goods_ids', 0);
        })->select();

        return $coupons;
    }


    protected function getSkuAttr($value, $data)
    {
        $sku = GoodsSku::all([
            'goods_id' => $data['id'],
            'pid' => 0,
        ]);
        foreach ($sku as $s => &$k) {
            $sku[$s]['content'] = GoodsSku::all([
                'goods_id' => $data['id'],
                'pid' => $k['id']
            ]);
        }
        return $sku;
    }

    private static function getSkuPrice($value, $data)
    {
        return GoodsSkuPrice::all([
            'goods_id' => $data['id'],
            'status' => 'up',
            'deletetime' => null
        ]);
    }


    public function getParamsAttr($value, $data)
    {
        return $value ? json_decode($value, true) : [];
    }


    public function getServiceAttr($value, $data)
    {
        $value = $data['service_ids'];
        $serviceData = [];
        if (!empty($value)) {
            $serviceArray = explode(',', $value);
            $serviceData = [];
            foreach ($serviceArray as $v) {
                $serviceData[] = \addons\shopro\model\GoodsService::get($v);
            }
        }
        return $serviceData;
    }

    public function getImageAttr($value, $data)
    {
        if (!empty($value)) return cdnurl($value, true);
    }

    public function getImagesAttr($value, $data)
    {
        $imagesArray = [];
        if (!empty($value)) {
            $imagesArray = explode(',', $value);
            foreach ($imagesArray as &$v) {
                $v = cdnurl($v, true);
            }
            return $imagesArray;
        }
        return $imagesArray;
    }


    public function getContentAttr($value, $data)
    {
        $content = $data['content'];
        $content = str_replace("<img src=\"/uploads", "<img style=\"width: 100%;!important\" src=\"" . request()->domain() . "/uploads", $content);
        $content = str_replace("<video src=\"/uploads", "<video style=\"width: 100%;!important\" src=\"" . request()->domain() . "/uploads", $content);
        return $content;
    }


    public function getDispatchTypeArrAttr($value, $data)
    {
        return array_filter(explode(',', $data['dispatch_type']));
    }

    public function favorite()
    {
        return $this->hasOne(\addons\shopro\model\UserFavorite::class, 'goods_id', 'id');
    }

    public function store()
    {
        return $this->hasOne(\addons\shopro\model\Store::class, 'id', 'store_id');
    }

    public function scoreGoodsSkuPrice()
    {
        return $this->hasMany(\addons\shopro\model\scoreGoodsSkuPrice::class, 'goods_id', 'id')
            ->where('status', 'up')->order('id', 'asc');
    }


    public function skuPrice()
    {
        return $this->hasMany(\addons\shopro\model\GoodsSkuPrice::class, 'goods_id', 'id')
            ->order('id', 'asc');
    }

    //商品列表排序
    private static function getGoodsListOrder($orderStr)
    {
        $order = 'weigh desc';
        $orderList = json_decode(htmlspecialchars_decode($orderStr), true);
        extract($orderList);
        if (isset($defaultOrder) && $defaultOrder === 1) {
            $order = 'weigh desc';
        }
        if (isset($priceOrder) && $priceOrder === 1) {
            $order = "convert(`price`, DECIMAL(10, 2)) asc";
        } elseif (isset($priceOrder) && $priceOrder === 2) {
            $order = "convert(`price`, DECIMAL(10, 2)) desc";
        }
        if (isset($salesOrder) && $salesOrder === 1) {
            $order = 'sales desc';
        }
        if (isset($newProdcutOrder) && $newProdcutOrder === 1) {
            $order = 'id desc';
        }
        return $order;
    }
}
