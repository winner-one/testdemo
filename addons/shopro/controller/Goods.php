<?php

namespace addons\shopro\controller;

use addons\shopro\exception\Exception;
use think\Db;
use think\Log as Logs;
use Yansongda\Pay\Log;

class Goods extends Base
{

    protected $noNeedLogin = ['index', 'detail', 'lists', 'activity', 'seckillList', 'grouponList', 'store'];
    protected $noNeedRight = ['*'];

    public function index()
    {
        // 测试，下面代码可删除
        // $redis = $this->getRedis();

        // $redis->HSET('aaaaa', 'bbb', 'smallnews');

        // // 获取活动集合
        // $hashList = $redis->ZRANGE('aaaaa', 0, 999999999);
        // var_dump($hashList);


        // $user = \addons\shopro\model\User::where('id', 57)->find();

        // $user->notify(
        //     new \addons\shopro\notifications\Order([
        //         // 'order' => \addons\shopro\model\Order::where('id', 359)->find(),
        //         // 'item' => \addons\shopro\model\OrderItem::where('id', 390)->find(),
        //         'order' => \addons\shopro\model\Order::where('id', 349)->find(),
        //         'item' => \addons\shopro\model\OrderItem::where('id', 380)->find(),
        //         'event' => 'order_sended'
        //     ])
        // );

        // $user = \addons\shopro\model\User::where('id', 2)->find();
        // $user->notify(
        //     new \addons\shopro\notifications\Aftersale([
        //         'aftersale' => \addons\shopro\model\OrderAftersale::get(6),
        //         'order' => \addons\shopro\model\Order::get(115),
        //         'aftersaleLog' => \addons\shopro\model\OrderAftersaleLog::get(9),
        //         'event' => 'aftersale_change'
        //     ])
        // );


        // $user = \addons\shopro\model\User::where('id', 2)->find();
        // $user->notify(
        //     new \addons\shopro\notifications\Wallet([
        //         'apply' => \addons\shopro\model\UserWalletApply::get(1),
        //         'event' => 'wallet_apply'
        //     ])
        // );

        // $user = \addons\shopro\model\User::where('id', 2)->find();
        // $user->notify(
        //     new \addons\shopro\notifications\Wallet([
        //         'walletLog' => \addons\shopro\model\UserWalletLog::get(1),
        //         'event' => 'wallet_change'
        //     ])
        // );
        // $user = \addons\shopro\model\User::where('id', 2)->find();
        // $user->notify(
        //     new \addons\shopro\notifications\store\Order([
        //         'store' => \addons\shopro\model\Store::get(1),
        //         'order' => \addons\shopro\model\Order::get(83),
        //         'event' => 'store_order_new'
        //     ])
        // );

    }

    public function detail()
    {
        $id = $this->request->get('id');
        $detail = \addons\shopro\model\Goods::getGoodsDetail($id);

        // 记录足记
        \addons\shopro\model\UserView::addView($detail);

        $sku_price = $detail['sku_price'];      // 处理过的规格
        // tp bug json_encode 或者 toArray 的时候 sku_price 会重新查询数据库，导致被处理过的规格又还原回去了
        $detail = json_decode(json_encode($detail), true);
        $detail['sku_price'] = $sku_price;

        $this->success('商品详情', $detail);
    }

    //查询可预约日期及对应价格
    public function ticket()
    {
        $id = $this->request->get('id');
        $skuId = $this->request->get('skuId');

        $detail = Db::name('shopro_goods')->where('id', $id)->find();
        $where = ['goods_id' => $id, 'deletetime' => null];
        $wheres = ['goods_id' => $id, 'deletetime' => null];
        if ($skuId != '0') {
            $where['goods_sku_ids'] = $skuId;
            $sku_price_id = Db::name('shopro_goods_sku_price')->where($where)->value('id');
            $wheres['sku_price_ids'] = $sku_price_id;
        }
        $price = Db::name('shopro_goods_sku_price')->where($where)->value('price');

        $date = [];
        for ($i = 1; $i <= $detail['days']; $i++) {
            $times = date('Y-m-d', strtotime('+' . $i - 1 . ' days', time()));
            $day_price = Db::name('shopro_goods_sku_days_price')->where('day', $times)->where($wheres)->value('price');
            $status = Db::name('shopro_goods_sku_days_price')->where('day', $times)->where($wheres)->value('status');
            if ($status != '0') {   //判断是否停售
                $arr['date'] = $times;
                if (((date('w', strtotime($times)) == 6) || (date('w', strtotime($times)) == 0)) && !$day_price) {   //如果是周末（且未设置指定日期价格）则进行涨价操作
                    $day_price = sprintf(" %1\$.2f", $price + $detail['rise_price']);    //整数转小数格式 保留两位小数
                }
                $arr['price'] = $day_price ? $day_price : $price;
                array_push($date, $arr);
            }
        }
        $detail['datePrice'] = $date;

        $this->success('商品详情', $detail);
    }

    //获取日期数组
    public static function getDays($liveNum, $day)
    {
        $days = [];
        for ($i = 1; $i <= $liveNum; $i++) {
            $times = date('Y-m-d', strtotime('+' . $i - 1 . ' days', strtotime($day)));
            array_push($days, $times);
        }
        Logs::write($days);
        return $days;
    }

    //查询预约日期的对应库存
    public function dayTicket()
    {
        $id = $this->request->get('id');
        $day = $this->request->get('day');
        $skuId = $this->request->get('skuId');

        $data['days'] = self::getDays($this->request->get('liveNum'), $day);        //将日期转换为数组

        $data['stock'] = \addons\shopro\model\Goods::getOrderStock($id, $day, $data['days'], $skuId);
        $this->success('商品详情', $data);
    }


    public function lists()
    {
        $params = $this->request->get();
        $data = \addons\shopro\model\Goods::getGoodsList($params);

        $this->success('商品列表', $data);
    }


    /**
     * 获取商品支持的 自提点
     */
    public function store()
    {
        $params = $this->request->get();
        $data = \addons\shopro\model\Goods::getGoodsStore($params);

        $this->success('自提列表', $data);
    }


    // 秒杀列表
    public function seckillList()
    {
        $params = $this->request->get();

        $this->success('秒杀商品列表', \addons\shopro\model\Goods::getSeckillGoodsList($params));
    }


    // 拼团列表
    public function grouponList()
    {
        $params = $this->request->get();

        $this->success('拼团商品列表', \addons\shopro\model\Goods::getGrouponGoodsList($params));
    }


    public function activity()
    {
        $activity_id = $this->request->get('activity_id');
        $activity = \addons\shopro\model\Activity::get($activity_id);
        if (!$activity) {
            throw new Exception('活动不存在', -1);
        }

        $goods = \addons\shopro\model\Goods::getGoodsList(['goods_ids' => $activity->goods_ids]);
        $activity->goods = $goods;

        $this->success('活动列表', $activity);
    }

    public function favorite()
    {
        $params = $this->request->post();
        $result = \addons\shopro\model\UserFavorite::edit($params);
        $this->success($result ? '收藏成功' : '取消收藏', $result);
    }

    public function favoriteList()
    {
        $data = \addons\shopro\model\UserFavorite::getGoodsList();
        $this->success('商品收藏列表', $data);
    }


    public function viewDelete()
    {
        $params = $this->request->post();
        $result = \addons\shopro\model\UserView::del($params);
        $this->success('删除成功', $result);
    }


    public function viewList()
    {
        $data = \addons\shopro\model\UserView::getGoodsList();
        $this->success('商品浏览列表', $data);
    }
}
