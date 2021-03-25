<?php

namespace addons\shopro\library\traits;

use addons\shopro\exception\Exception;
use addons\shopro\library\Redis;
use addons\shopro\model\ActivityGoodsSkuPrice;
use addons\shopro\model\Goods;
use addons\shopro\model\GoodsSkuPrice;
use addons\shopro\model\OrderItem;
use addons\shopro\model\ScoreGoodsSkuPrice;

/**
 * 活动 redis 缓存
 */
trait ActivityCache
{
    protected $zsetKey = 'zset-activity';
    protected $hashPrefix = 'hash-activity:';
    protected $hashGoodsPrefix = 'goods-';
    protected $hashGrouponPrefix = 'groupon-';


    public function hasRedis($is_interrupt = false) {
        $error_msg = '';
        try {
            $redis = $this->getRedis();

            // 检测连接是否正常
            $redis->ping();
        } catch (\BadFunctionCallException $e) {
            // 缺少扩展
            $error_msg = $e->getMessage() ? $e->getMessage() : "缺少 redis 扩展";
        } catch (\RedisException $e) {
            // 连接拒绝
            \think\Log::write('redis connection redisException fail: ' . $e->getMessage());
            $error_msg = $e->getMessage() ? $e->getMessage() : "redis 连接失败";
        } catch (\Exception $e) {
            // 异常
            \think\Log::write('redis connection fail: ' . $e->getMessage());
            $error_msg = $e->getMessage() ? $e->getMessage() : "redis 连接异常";
        }

        if ($error_msg) {
            if ($is_interrupt) {
                throw new \Exception($error_msg);
            } else {
                return false;
            }
        }

        return true;
    }


    public function getRedis() {
        if (!isset($GLOBALS['SPREDIS'])) {
            $GLOBALS['SPREDIS'] = (new Redis())->getRedis();
        }

        return $GLOBALS['SPREDIS'];
    }


    // 将活动设置到缓存
    public function setActivity($activity, $goodsList = []) {
        $redis = $this->getRedis();

        // hash 键值
        $hashKey = $this->getHashKey($activity['id'], $activity['type']);

        // 删除旧的可变数据，需要排除销量 key 
        if ($redis->EXISTS($hashKey)) {
            // 如果 hashKey 存在,删除规格
            $hashs = $redis->HGETALL($hashKey);

            foreach ($hashs as $hashField => $hashValue) {
                // 是商品规格，并且不是销量
                if (strpos($hashField, $this->hashGoodsPrefix) !== false && strpos($hashField, '-sale') === false) {
                    // 商品规格信息，删掉
                    $redis->HDEL($hashKey, $hashField);
                }
            }
        }

        $redis->HMSET($hashKey, [
                            'id' => $activity['id'],
                            'title' => $activity['title'],
                            'type' => $activity['type'],
                            'richtext_id' => $activity['richtext_id'],
                            'richtext_title' => $activity['richtext_title'],
                            'starttime' => $activity['starttime'],
                            'endtime' => $activity['endtime'],
                            'rules' => is_array($activity['rules']) ? json_encode($activity['rules']) : $activity['rules'],
                            'goods_ids' => $activity['goods_ids']
                        ]
                    );

        foreach ($goodsList as $goods) {
            $redis->HSET($hashKey, $this->getHashGoodsKey($goods['goods_id'], $goods['sku_price_id']), json_encode($goods));
        }

        // 将 hash 键值存入 有序集合，score 为 id
        $redis->ZADD($this->zsetKey, $activity['starttime'], $hashKey);
    }


    // 获取所有活动
    public function getActivityList($activity_type = '', $status = 'all') {
        $redis = $this->getRedis();

        // 获取活动集合
        $hashList = $redis->ZRANGE($this->zsetKey, 0, 999999999);

        $activityList = [];
        if (!$hashList) {       // 没有获取到，返回空数组
            return $activityList;
        }

        foreach ($hashList as $hashKey) {
            // 查询活动状态
            if ($status != 'all') {
                $starttime = $redis->HGET($hashKey, 'starttime');
                $endtime = $redis->HGET($hashKey, 'endtime');
                
                if ($status == 'ing') {
                    if (time() < $starttime || time() > $endtime) {
                        continue;
                    }
                } else if ($status == 'nostart') {
                    if (time() > $starttime) {
                        continue;
                    }
                } else if ($status == 'ended') {
                    if (time() < $endtime) {
                        continue;
                    }
                }
            }

            if ($activity_type) {
                $type = $redis->HGET($hashKey, 'type');

                if ($activity_type == $type) {
                    $activity = $this->getActivityByHashKey($hashKey);
                    if ($activity) {
                        $activityList[] = $activity;
                    }
                }
            } else {
                $activity = $this->getActivityByHashKey($hashKey);
                if ($activity) {
                    $activityList[] = $activity;
                }
            }
        }

        return $activityList;
    }


    // 获取商品对应的活动
    public function getGoodsActivity($goods_id, $activity_id = 0, $activity_type = '') {
        // 获取活动的 hash key
        $activityHashKey = $this->getActivityHashKey($goods_id, $activity_id, $activity_type);

        // 如果存在活动
        if ($activityHashKey) {
            return $this->getGoodsActivityByHashKey($goods_id, $activityHashKey);
        }

        return null;
    }



    // 删除活动缓存
    public function delActivity($activity) {
        $redis = $this->getRedis();

        $hashKey = $this->getHashKey($activity['id'], $activity['type']);

        // 删除 hash
        $redis->DEL($hashKey);

        // 删除集合
        $redis->ZREM($this->zsetKey, $hashKey);
    }


    // 通过 hash key 获取活动信息,商品信息获取商品相关活动信息
    private function getGoodsActivityByHashKey($goods_id, $activityHashKey) {
        $redis = $this->getRedis();

        // 取出整条 hash 记录
        $activityHash = $redis->HGETALL($activityHashKey);

        $activity = [];
        // 当前商品前缀
        $goodsPrefix = $this->hashGoodsPrefix . $goods_id . '-';

        foreach ($activityHash as $key => $value) {
            if (strpos($key, $goodsPrefix) !== false && strpos($key, '-sale') === false) {
                $goods = json_decode($value, true);

                // 计算销量库存数据
                $goods = $this->calcGoods($goods, $activityHashKey);

                // 商品规格项
                $activity['activity_goods_sku_price'][] = $goods;
            } else if (strpos($key, $this->hashGoodsPrefix) !== false) {
                // 活动里面别的商品规格项移除
                continue;
            } else if (strpos($key, $this->hashGrouponPrefix) !== false && (strpos($key, '-num') !== false || strpos($key, '-userlist') !== false)) {
                // 拼团的参团人数，团用户，移除
                continue;
            } else if ($key == 'rules') {
                $activity[$key] = json_decode($value, true);
            } else {
                // 普通键值
                $activity[$key] = $value;
            }
        }

        return $activity ? : null;
    }


    // 获取活动所有信息
    private function getActivityByHashKey($activityHashKey)
    {
        $redis = $this->getRedis();

        // 取出整条 hash 记录
        $activityHash = $redis->HGETALL($activityHashKey);

        $activity = [];

        foreach ($activityHash as $key => $value) {
            if (strpos($key, $this->hashGoodsPrefix) !== false && strpos($key, '-sale') === false) {
                $goods = json_decode($value, true);

                // 计算销量库存数据
                $goods = $this->calcGoods($goods, $activityHashKey);

                // 商品规格项
                $activity['activity_goods_sku_price'][] = $goods;
            } else if (strpos($key, $this->hashGoodsPrefix) !== false && strpos($key, '-sale') !== false) {
                // 活动里面 商品规格的销量，移除
                continue;
            } else if (strpos($key, $this->hashGrouponPrefix) !== false && (strpos($key, '-num') !== false || strpos($key, '-userlist') !== false)) {
                // 拼团的参团人数，团用户，移除
                continue;
            } else if ($key == 'rules') {
                $activity[$key] = json_decode($value, true);
            } else {
                // 普通键值
                $activity[$key] = $value;
            }
        }

        return $activity ?: null;
    }


    // 计算销量库存
    private function calcGoods($goods, $activityHashKey) {
        $redis = $this->getRedis();

        // 销量 key 
        $saleKey = $this->getHashGoodsKey($goods['goods_id'], $goods['sku_price_id'], true);
        
        // 缓存中的销量
        $cacheSale = $redis->HGET($activityHashKey, $saleKey);

        $stock = $goods['stock'] - $cacheSale; 
        $goods['stock'] = $stock > 0 ? $stock : 0;
        $goods['sales'] = $goods['sales'] + $cacheSale;

        return $goods;
    }


    // 从集合里面取出 当前要用的活动的 key
    private function getActivityHashKey($goods_id, $activity_id = 0, $activity_type = '') {
        $redis = $this->getRedis();

        // 获取活动集合
        $hashList = $redis->ZRANGE($this->zsetKey, 0, 999999999);

        $activityHashKey = '';
        if (!$hashList) {       // 没有获取到，返回空数组
            return $activityHashKey;
        }
        foreach ($hashList as $hashKey) {
            // 是否传入活动 id
            if ($activity_id && $activity_type) {
                $currentKey = $activity_type . ':' . $activity_id;

                // 判断是否是当前要找的活动
                if (strpos($hashKey, $currentKey) !== false) {
                    // 判断该活动中是否存在该商品
                    $goods_ids = explode(',', $redis->HGET($hashKey, 'goods_ids'));
                    if (in_array($goods_id, $goods_ids)) {
                        $activityHashKey = $hashKey;
                        break;
                    } else {
                        // 抛出异常， 活动不存在
                        throw new Exception('活动不存在');
                    }
                }

                // 接着找下一个活动
            } else {
                // 判断这条活动是否包含该商品
                $goods_ids = explode(',', $redis->HGET($hashKey, 'goods_ids'));
                if (in_array($goods_id, $goods_ids)) {
                    $activityHashKey = $hashKey;
                    break;
                }

                // 接着找下一个活动
            }
        }

        return $activityHashKey;
    }



    // 拼接 hash key
    private function getHashKey($activity_id, $activity_type) {
        return $this->hashPrefix . $activity_type . ':' . $activity_id;
    }


    // 拼接 hash 表中 goods 的 key
    private function getHashGoodsKey($goods_id, $sku_price_id, $is_sale = false)
    {
        return $this->hashGoodsPrefix . $goods_id . '-' . $sku_price_id . ($is_sale ? '-sale' : '');
    }



    // 拼接 hash 表中 groupon 的 key
    private function getHashGrouponKey($groupon_id, $goods_id, $type = '')
    {
        return $this->hashGrouponPrefix . $groupon_id . '-' . $goods_id . ($type ? '-' . $type : '');
    }


    // 获取 key 集合
    public function getKeys($detail, $activity)
    {
        // 获取 hash key
        $activityHashKey = $this->getHashKey($activity['activity_id'], $activity['activity_type']);

        $goodsSkuPriceKey = '';
        $saleKey = '';
        if (isset($detail['goods_sku_price_id']) && $detail['goods_sku_price_id']) {
            // 获取 hash 表中商品 sku 的 key
            $goodsSkuPriceKey = $this->getHashGoodsKey($detail['goods_id'], $detail['goods_sku_price_id']);
            // 获取 hash 表中商品 sku 的 销量的 key
            $saleKey = $this->getHashGoodsKey($detail['goods_id'], $detail['goods_sku_price_id'], true);
        }

        // 需要拼团的字段
        $grouponKey = '';
        $grouponNumKey = '';
        $grouponUserlistKey = '';
        if (isset($detail['groupon_id']) && $detail['groupon_id']) {
            // 获取 hash 表中团 key
            $grouponKey = $this->getHashGrouponKey($detail['groupon_id'], $detail['goods_id']);
            // 获取 hash 表中团当前人数 key
            $grouponNumKey = $this->getHashGrouponKey($detail['groupon_id'], $detail['goods_id'], 'num');
            // 获取 hash 表中团当前人员列表 key
            $grouponUserlistKey = $this->getHashGrouponKey($detail['groupon_id'], $detail['goods_id'], 'userlist');
        }

        return compact('activityHashKey', 'goodsSkuPriceKey', 'saleKey', 'grouponKey', 'grouponNumKey', 'grouponUserlistKey');
    }
}
