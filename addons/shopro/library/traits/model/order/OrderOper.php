<?php

namespace addons\shopro\library\traits\model\order;

use addons\shopro\exception\Exception;
use addons\shopro\model\ActivityGoodsSkuPrice;
use addons\shopro\model\Cart;
use addons\shopro\model\Config;
use addons\shopro\model\Coupons;
use addons\shopro\model\User;
use addons\shopro\model\ScoreGoodsSkuPrice;
use addons\shopro\model\Goods;
use addons\shopro\model\UserAddress;
use addons\shopro\model\Dispatch;
use addons\shopro\model\GoodsComment;
use addons\shopro\model\GoodsSkuPrice;
use addons\shopro\model\UserCoupons;
use addons\shopro\model\Order;
use addons\shopro\model\OrderAction;
use addons\shopro\model\OrderItem;
use addons\shopro\model\ActivityGroupon;
use addons\shopro\model\Store;
use addons\shopro\model\Verify;
use app\admin\model\shopro\order\RefundLog;
use think\Cache;
use think\Db;
use think\Log;

trait OrderOper
{
    use OrderOperSendGet;

    // 获取订单号
    public static function getSn($user_id)
    {
        $rand = $user_id < 9999 ? mt_rand(100000, 99999999) : mt_rand(100, 99999);
        $order_sn = date('Yhis') . $rand;

        $id = str_pad($user_id, (24 - strlen($order_sn)), '0', STR_PAD_BOTH);

        return $order_sn . $id;
    }


    // 计算订单
    public static function pre($params, $calc_type = 'pre')
    {
        $user = User::info();

        extract($params);
        $order_type = $order_type ?? 'goods';
        $groupon_id = $groupon_id ?? 0;        // 拼团的 团 id
        $buy_type = $buy_type ?? 'alone';        // 拼团的 购买方式: alone=单独购买,groupon=开团

        $activity_type = '';
        $new_goods_list = [];
        foreach ($goods_list as $key => $buyinfo) {
            // 最少购买一件
            $buyinfo['goods_num'] = intval($buyinfo['goods_num']) < 1 ? 1 : intval($buyinfo['goods_num']);

            $sku_price_id = $buyinfo['sku_price_id'];
            $activity = null;

            if ($order_type == 'score') {
                // 积分商城商品详情
                $detail = ScoreGoodsSkuPrice::getGoodsDetail($buyinfo['goods_id']);
            } else {
                $detail = Goods::getGoodsDetail($buyinfo['goods_id']);
                // 如果有活动，判断活动是否正在进行中
                if (isset($detail['activity']) && $detail['activity']) {
                    $activity = $detail['activity'];
                    if ($activity['starttime'] > time()) {
                        throw new Exception('商品活动未开始');
                    }

                    if ($activity['endtime'] < time()) {
                        throw new Exception('商品活动已结束');
                    }
                }
            }

            $sku_prices = $detail['sku_price'];
            foreach ($sku_prices as $key => $sku_price) {
                if ($sku_price['id'] == $sku_price_id) {
                    $detail->current_sku_price = $sku_price;
                    break;
                }
            }

            if (!$detail || ($order_type != 'score' && $detail->status === 'down')) {
                throw new Exception('商品不存在或已下架');
            }

            if (!isset($detail->current_sku_price) || !$detail->current_sku_price) {
                throw new Exception('商品规格不存在');
            }

            // 判断商品是否选择了配送方式
            if (!isset($buyinfo['dispatch_type']) || empty($buyinfo['dispatch_type'])) {
                // 不存在，或者为空，默认获第一个
                $current_dispatch_type = array_filter(explode(',', $detail['dispatch_type']));
                $buyinfo['dispatch_type'] = $current_dispatch_type[0] ?? '';
            }

            if ($calc_type == 'create') {
                if (empty($buyinfo['dispatch_type'])) {
                    throw new Exception("请选择配送方式");
                }

                if ($buyinfo['dispatch_type'] == 'selfetch' && (!isset($buyinfo['store_id']) || !$buyinfo['store_id'])) {
                    if (!$detail->is_ticket) {
                        throw new Exception("请选择门店");
                        // throw new Exception("请选择自提点");
                    } else {
                        // $buyinfo['store_id'] = Db::name('shopro_dispatch_selfetch')->where('id', Db::name('shopro_dispatch')->where('id', $detail->dispatch_ids)->value('type_ids'))->value('store_ids');
                    }
                }
            }

            // 组装 商品详情
            $buyinfo['detail'] = $detail;
            $new_goods_list[] = $buyinfo;
            $day = null;
            // 判断是否属预约产品，判断是否是节假日
            if ($detail->is_ticket != '0') {
                $day = !empty($buyinfo['day']) ? $buyinfo['day'] : $day;
                // $skuId = Db::name('shopro_goods_sku_price')->where('id', $sku_price_id)->where('deletetime', null)->value('goods_sku_ids');
                // $where = ['item.goods_id' => $detail->id];
                // if ($detail->current_sku_price->goods_sku_ids) {    //判断单规格还是多规格
                //     $where['item.goods_sku_price_id'] = $sku_price_id;
                // }
                // $day_stock = Db::name('shopro_order')->alias('order')->join("shopro_order_item item", 'order.id=item.order_id', 'LEFT')->where('item.day', $day)->where('item.aftersale_status', '<>', '2')->where('item.refund_status', '<>', '2')->where($where)->where('order.status', 'in', [0, 1, 2])->sum('goods_num');
                // $day_stock = 99;
                $where = ['goods_id' => $detail->id];
                if ($detail->current_sku_price->goods_sku_ids) {    //判断单规格还是多规格
                    $where['sku_price_ids'] = $sku_price_id;
                }
                $info = Db::name('shopro_goods_sku_days_price')->where('day', $day)->where($where)->where('deletetime', null)->find();
                if ($info && $info['status'] != '1') {  //判断当天是否停止预约
                    throw new Exception("日期：" . $day . "停售");
                }
                if ($info) {
                    $stock = $info['stock'];
                } else {
                    $stock = Db::name('shopro_goods_sku_price')->where('id', $sku_price_id)->where('deletetime', null)->value('stock');
                }
                $day_stock = \addons\shopro\model\Goods::getDayStock($detail->id, $day, $sku_price_id);     //获取指定日期库存
                Log::write('当天已销售：' . $day_stock . ';当天总库存：' . $stock);
                if ($stock <= $day_stock) {
                    throw new Exception('来晚啦，' . $day . '号的库存已被抢购完了');
                }
                if ($info['price']) {
                    $detail->current_sku_price->price = $info['price'];
                } else {
                    if ((date('w', strtotime($day)) == 6) || (date('w', strtotime($day)) == 0)) {
                        // Log::write($detail->rise_price);
                        // Log::write('你输入的日期是周末');
                        $detail->current_sku_price->price = $detail->current_sku_price->price + $detail->rise_price;
                    } else {
                        // Log::write('当然也不是周末了');
                    }
                }
            }

            // 要购买的数量
            $need_goods_num = $buyinfo['goods_num'];
            // 开团需要的最小库存
            $groupon_num = 0;
            // 是否允许单独购买
            $is_alone = 1;
            if (isset($detail['activity_type']) && $detail['activity_type']) {
                $activity_type .= $detail['activity_type'] . ',';

                if ($detail['activity_type'] == 'groupon') {
                    // 拼团
                    $rules = $activity['rules'];
                    $is_alone = $rules['is_alone'] ?? 1;
                    // 成团人数
                    $num = $rules['team_num'] ?? 1;

                    // 要单独购买
                    if ($buy_type == 'alone') {
                        // 不允许单独购买
                        if (!$is_alone) {
                            throw new Exception('该商品不允许单独购买');
                        }
                    } else {
                        // 拼团，临时将拼团加设置为商品价格
                        $detail->current_sku_price->price = $detail->current_sku_price->groupon_price;
                    }

                    // 如果是开新团
                    if (!$groupon_id && $buy_type == 'groupon') {
                        // 开团需要的最小库存
                        $groupon_num = ($num - 1);
                    }
                }
            }

            if ($detail->current_sku_price['stock'] < ($buyinfo['goods_num'] + $groupon_num)) {
                if ($detail->current_sku_price['stock'] < $buyinfo['goods_num']) {
                    // 不够自己买
                    throw new Exception('商品库存不足');
                } else if ($groupon_num && $is_alone && !$groupon_id && $buy_type == 'groupon') {
                    // 够自己买，但不够开新团，并且允许单独购买，提醒可以单独购买
                    throw new Exception('商品库存不足以开团，请选择单独购买');
                }
            }
        }

        // 多活动拼接去掉多余的 ,
        $activity_type = rtrim($activity_type, ',');

        if (!count($new_goods_list)) {
            throw new Exception('请选择要购买的商品');
        }

        if (strpos($activity_type, 'seckill') !== false && count($new_goods_list) > 1) {
            throw new Exception('秒杀商品必须单独购买');
        }
        if (strpos($activity_type, 'groupon') !== false && count($new_goods_list) > 1) {
            throw new Exception('拼团商品必须单独购买');
        }
        if ($order_type == 'score' && count($new_goods_list) > 1) {
            throw new Exception('积分商品必须单独购买');
        }

        $need_address = 1;
        $user_address = null;
        // 判断是否有需要收货地址的商品，新版每个商品需要选择配送方式
        $dispatchTypes = array_column($new_goods_list, 'dispatch_type');
        if (in_array('express', $dispatchTypes) || in_array('store', $dispatchTypes)) {
            // 配送方式为 快递 或者 自提 必须填写收货地址
            $user_address = UserAddress::where("user_id", $user->id)->find($address_id);

            if (is_null($user_address) && $calc_type == 'create') {
                throw new Exception("请选择正确的收货地址");
            }
        } else {
            // 不需要收货地址
            $need_address = 0;
        }

        $goods_original_amount = 0;         // 商品原始总价
        $goods_amount = 0;                  // 商品总价
        $dispatch_amount = 0;               // 运费总价
        $total_amount = 0;                  // 订单总金额
        $score_amount = 0;                  // 订单总积分
        $total_fee = 0;                     // 支付金额 
        $discount_fee = 0;                  // 折扣总金额
        $coupon_fee = 0;                    // 优惠券金额
        $score_fee = 0;                     // 支付积分

        // 计算商品金额
        foreach ($new_goods_list as $key => $buyinfo) {
            $detail = $buyinfo['detail'];

            // 当前商品原始总价
            $current_goods_original_amount = ($detail->original_price * $buyinfo['goods_num']);
            $goods_original_amount += $current_goods_original_amount;

            // 当前商品现在总价
            $current_goods_amount = ($detail->current_sku_price->price * $buyinfo['goods_num']);
            $goods_amount += $current_goods_amount;

            // 获取配送数据
            if ($buyinfo['dispatch_type']) {
                $dispatchData = Dispatch::getDispatch($buyinfo['dispatch_type'], $detail, [
                    'address' => $user_address,
                    'goods_num' => $buyinfo['goods_num'],
                ]);
            }

            // 配送模板 id
            $current_dispatch_id = $dispatchData['dispatch_id'] ?? 0;
            // 配送费
            $current_dispatch_amount = $dispatchData['dispatch_amount'] ?? 0;
            // 如果商家配送，默认选择最近的门店
            $current_store_id = $buyinfo['store_id'] ?? 0;
            if (
                $buyinfo['dispatch_type'] == 'store'
                && isset($dispatchData['store'])
                && $dispatchData['store']
            ) {
                // 商家配送,自动采用最近的 store
                $current_store_id = $dispatchData['store']['id'];
            }

            // 累加配送费
            $dispatch_amount += $current_dispatch_amount;

            // 当前商品所需积分
            $current_score_amount = 0;
            if ($order_type == 'score') {       // 积分商城规格
                $current_score_amount = ($detail->current_sku_price->score * $buyinfo['goods_num']);
                $score_amount += $current_score_amount;
            }

            // 将计算好的属性记录下来，插入订单 item 表使用
            $new_goods_list[$key]['goods_original_amount'] = $current_goods_original_amount;
            $new_goods_list[$key]['goods_amount'] = $current_goods_amount;
            $new_goods_list[$key]['dispatch_amount'] = $current_dispatch_amount;
            $new_goods_list[$key]['score_amount'] = $current_score_amount;
            $new_goods_list[$key]['dispatch_id'] = $current_dispatch_id;
            $new_goods_list[$key]['store_id'] = $current_store_id;
        }

        // 计算优惠券费用
        $user_coupons = null;
        if ($coupons_id) {
            if (!empty($activity_type)) {
                throw new Exception('活动商品不可使用优惠券');
            }

            if ($order_type == 'score') {
                throw new Exception('积分商品不可使用优惠券');
            }

            // 查询传来的优惠券 id 是否可用
            $coupons = self::coupons($params, $goods_amount);

            $current_coupons = null;        // 当前所选优惠券
            foreach ($coupons as $key => $coupon) {
                if ($coupon['user_coupons_id'] == $coupons_id) {
                    $current_coupons = $coupon;
                    break;
                }
            }

            if ($current_coupons) {
                $coupon_fee = $current_coupons->amount;     // 金额在 coupons 表存着
                $user_coupons = UserCoupons::where('id', $coupons_id)->find();        // 用户优惠券
            } else {
                throw new Exception('优惠券不可用');
            }
        }

        $total_amount = $goods_amount + $dispatch_amount;
        $discount_fee = $coupon_fee;
        // $total_fee = $total_amount - $discount_fee;
        $total_fee = ($total_amount - $discount_fee) >= 0 ? ($total_amount - $discount_fee) : 0.00;     //避免支付为负数的情况

        // 在这里将优惠按金额平均分割到每个订单 item 上
        // 计算每一块钱应该分配的优惠金额
        $every_discount_fee = 0;
        if ($goods_amount) {
            $every_discount_fee = round(($discount_fee / $goods_amount), 4);
        }
        foreach ($new_goods_list as $key => $buyinfo) {
            $new_goods_list[$key]['discount_fee'] = round(($buyinfo['goods_amount'] * $every_discount_fee), 2);
        }

        $result = compact(
            "goods_original_amount",
            "goods_amount",
            "dispatch_amount",
            "total_amount",
            "total_fee",
            "discount_fee",
            "coupon_fee"
        );

        // 处理小数点保留两位小数
        foreach ($result as $key => $amount) {
            $result[$key] = number_format($amount, 2, '.', '');
        }

        // 合并不需要处理小数点的
        $result = array_merge($result, compact(
            "activity_type",
            "score_amount",
            "new_goods_list",
            "need_address"
        ));

        // 如果是下单，合并 优惠券， 收货地址
        if ($calc_type == 'create') {
            $result = array_merge($result, compact(
                "user_coupons",
                "user_address"
            ));
        }

        return $result;
    }


    // 获取可用优惠券列表
    public static function coupons($params, $goods_amount = 0)
    {
        $user = User::info();
        extract($params);
        $order_type = $order_type ?? 'goods';
        $groupon_id = $groupon_id ?? 0;        // 拼团的 团 id
        $buy_type = $buy_type ?? 'alone';       // 拼团的 购买方式: alone=单独购买,groupon=开团

        // 商品总金额
        if (!$goods_amount) {
            // 下单的时候把计算好的 goods_amount 传进来了，接口直接获取可用列表的时候，需要计算
            foreach ($goods_list as $k => $goods) {
                $goods_amount += ($goods['goods_price'] * $goods['goods_num']);
            }
        }

        $coupons = Coupons::getCouponsList(Coupons::COUPONS_CAN_USE);

        $new_coupons = [];
        // 过滤，如果有一个产品不适用，则优惠券不允许使用,不显示
        foreach ($coupons as $key => $coupon) {
            if ($coupon['goods_ids'] === '0') {
                // 所有商品可用
                $can_use = true;
            } else {
                $goods_ids = explode(',', $coupon['goods_ids']);

                $can_use = true;
                foreach ($goods_list as $k => $goods) {
                    if (!in_array($goods['goods_id'], $goods_ids)) {
                        $can_use = false;
                        break;
                    }
                }
            }

            // 商品可用 并且 商品金额满足
            if ($can_use && $coupon->enough <= $goods_amount) {
                $new_coupons[] = $coupon;
            }
        }

        $new_coupons = array_values($new_coupons);

        return $new_coupons;
    }


    public static function createOrder($params)
    {
        $user = User::info();

        // 入参
        extract($params);
        $remark = $remark ?? null;
        $order_type = $order_type ?? 'goods';
        $groupon_id = $groupon_id ?? 0;        // 拼团的 团 id
        $buy_type = $buy_type ?? 'alone';       // 拼团的 购买方式: alone=单独购买,groupon=开团

        // 订单计算数据
        extract(self::pre($params, "create"));
        if (empty($day)) {
            $day = null;
        }
        $order = Db::transaction(function () use (
            $day,
            $user,
            $order_type,
            $groupon_id,
            $buy_type,
            $goods_original_amount,
            $goods_amount,
            $dispatch_amount,
            $total_amount,
            $score_amount,
            $total_fee,
            $discount_fee,
            $coupon_fee,
            $new_goods_list,
            $activity_type,
            $user_coupons,
            $user_address,
            $remark,
            $from
        ) {
            // 订单创建前
            $data = [
                'user' => $user,
                'order_type' => $order_type,
                'groupon_id' => $groupon_id,
                'buy_type' => $buy_type,
                'goods_original_amount' => $goods_original_amount,
                'goods_amount' => $goods_amount,
                'dispatch_amount' => $dispatch_amount,
                'total_amount' => $total_amount,
                'score_amount' => $score_amount,
                'total_fee' => $total_fee,
                'discount_fee' => $discount_fee,
                'coupon_fee' => $coupon_fee,
                'new_goods_list' => $new_goods_list,
                'activity_type' => $activity_type,
                'user_coupons' => $user_coupons,
                'user_address' => $user_address,
                'remark' => $remark,
                'from' => $from
            ];
            // 如果是活动，这里面减掉 redis 库存
            \think\Hook::listen('order_create_before', $data);
            // 团信息, 如果是参与旧团拼团才会不为 null，（开新团放到支付成功）
            $groupon = cache('grouponinfo-' . $user->id);
            $groupon = $groupon ? json_decode($groupon, true) : null;
            // 立即清除缓存
            cache('grouponinfo-' . $user->id, NULL);

            $orderData = [];
            $orderData['order_sn'] = self::getSn($user->id);
            $orderData['user_id'] = $user->id;
            $orderData['type'] = $order_type;
            $orderData['activity_type'] = $activity_type;
            $orderData['goods_amount'] = $goods_amount;
            $orderData['dispatch_amount'] = $dispatch_amount;
            $orderData['total_amount'] = $total_amount;
            $orderData['score_amount'] = $score_amount;
            $orderData['total_fee'] = $total_fee;
            $orderData['discount_fee'] = $discount_fee;
            $orderData['score_fee'] = $score_amount;          // 记录score 支付数
            $orderData['coupon_fee'] = $coupon_fee;
            $orderData['goods_original_amount'] = $goods_original_amount;

            if ($user_address) {
                $orderData['phone'] = $user_address->phone;
                $orderData['consignee'] = $user_address->consignee;
                $orderData['province_name'] = $user_address->province_name;
                $orderData['city_name'] = $user_address->city_name;
                $orderData['area_name'] = $user_address->area_name;
                $orderData['address'] = $user_address->address;
                $orderData['province_id'] = $user_address->province_id;
                $orderData['city_id'] = $user_address->city_id;
                $orderData['area_id'] = $user_address->area_id;
            }
            $orderData['status'] = 0;
            $orderData['remark'] = $remark;
            $orderData['coupons_id'] = $user_coupons ? $user_coupons->id : 0;
            $orderData['platform'] = request()->header('platform');
            // $orderData['pay_fee'] = $pay_fee;          // 回调填充
            // $orderData['score_fee'] = $score_fee;          // 回调填充
            // $orderData['transaction_id'] = transaction_id;
            // $orderData['payment_json'] = payment_json;
            // $orderData['pay_type'] = pay_type;
            // $orderData['paytime'] = paytime;
            $orderData['ext'] = json_encode([]);
            $order = new Order();
            $order->allowField(true)->save($orderData);

            // 将优惠券使用掉
            if ($user_coupons) {
                $user_coupons->use_order_id = $order->id;
                $user_coupons->usetime = time();
                $user_coupons->save();
            }

            // 如果需要支付积分,扣除积分
            if ($score_amount) {
                // $user 为 Common\Auth 对象
                User::scoreReduce($user->id, $score_amount, 'score_pay', $order->id, [
                    'order_id' => $order->id,
                    'order_sn' => $order->order_sn,
                ]);
            }

            $partner = json_decode(\addons\shopro\model\Config::where(['name' => 'partner'])->value('value'), true);
            $auth = \app\common\library\Auth::instance();
            // 添加 订单 item
            foreach ($new_goods_list as $key => $buyinfo) {
                $detail = $buyinfo['detail'];
                // Log::write($detail);
                $current_sku_price = $detail['current_sku_price'];

                $orderItem = new OrderItem();

                $orderItem->user_id = $user->id;
                $orderItem->order_id = $order->id;
                $orderItem->goods_id = $buyinfo['goods_id'];
                $orderItem->goods_type = $detail['type'];
                $orderItem->goods_sku_price_id = $buyinfo['sku_price_id'];

                $orderItem->activity_id = $current_sku_price['activity_id'] ?? 0;     // 商品当前的活动类型
                $orderItem->activity_type = $current_sku_price['activity_type'] ?? null;     // 商品当前的活动类型
                // 当前商品规格对应的 活动下对应商品规格的 id
                $orderItem->item_goods_sku_price_id = isset($current_sku_price['item_goods_sku_price']) ?
                    $current_sku_price['item_goods_sku_price']['id'] : 0;
                $orderItem->goods_sku_text = $current_sku_price['goods_sku_text'];
                $orderItem->goods_title = $detail->title;
                $orderItem->day = $buyinfo['day'] ?? $day;
                $orderItem->goods_image = empty($current_sku_price['image']) ? $detail->image : $current_sku_price['image'];
                $orderItem->goods_original_price = $detail->original_price;
                $orderItem->discount_fee = $buyinfo['discount_fee'];        // 平均计算单件商品所享受的折扣
                $orderItem->goods_price = $detail->current_sku_price->price;
                $orderItem->goods_num = $buyinfo['goods_num'] ?? 1;
                $orderItem->dispatch_status = 0;
                $orderItem->dispatch_fee = $buyinfo['dispatch_amount'];
                $orderItem->dispatch_type = $buyinfo['dispatch_type'];
                $orderItem->dispatch_id = $buyinfo['dispatch_id'] ? $buyinfo['dispatch_id'] : 0;
                $orderItem->store_id = $buyinfo['store_id'] ? $buyinfo['store_id'] : $detail['store_id'];
                $store_id = $buyinfo['store_id'] ? $buyinfo['store_id'] : $detail['store_id'];
                $orderItem->aftersale_status = 0;
                $orderItem->comment_status = 0;
                $orderItem->refund_status = 0;

                //佣金计算
                // $orderItem->one_com = ($detail->current_sku_price->price -  $buyinfo['discount_fee']) * $partner['one_com'];
                // $orderItem->two_com = ($detail->current_sku_price->price -  $buyinfo['discount_fee']) * $partner['two_com'];

                $ext = [];
                if (isset($buyinfo['dispatch_date'])) {
                    $ext['dispatch_date'] = $buyinfo['dispatch_date'];
                }
                if (isset($buyinfo['day']) && isset($buyinfo['hour'])) {
                    $ext['dispatch_date'] = $buyinfo['day'] . ' ' . $buyinfo['hour'];
                }
                if (isset($buyinfo['dispatch_phone'])) {
                    $ext['dispatch_phone'] = $buyinfo['dispatch_phone'];
                }
                $orderItem->ext = json_encode($ext);
                $orderItem->save();
                // $orderItem->express_name = $express_name;
                // $orderItem->express_no = $express_no;
            }

            // 订单创建后
            $data = [
                'user' => $user,
                'order' => $order,
                'from' => $from,
                'groupon' => $groupon,
                'buy_type' => $buy_type,
                'activity_type' => $activity_type,
                'new_goods_list' => $new_goods_list
            ];
            \think\Hook::listen('order_create_after', $data);

            // 重新获取订单
            $order = self::where('id', $order['id'])->find();
            // $order->referrer_id = Db::name('user')->where('id', $user->id)->value('referrer_id');
            $order->referrer_id = $auth->referrer_id;
            $order->referrer_ids = $auth->referrer_ids;
            $order->store_id = $store_id;
            $order->save();

            return $order;
        });

        return $order;
    }


    // 订单列表
    public static function getList($params)
    {
        $user = User::info();
        extract($params);

        $order = (new self())->where('user_id', $user->id)->with('item');

        switch ($type) {
            case 'all':
                $order = $order;
                break;
            case 'nopay':
                $order = $order->nopay();
                break;
            case 'nosend':
                $order = $order->payed()->nosend();
                break;
            case 'noget':
                $order = $order->payed()->noget();
                break;
            case 'nocomment':
                $order = $order->payed()->nocomment();
                break;
            case 'aftersale':
                $order = $order->payed()->aftersale();
                break;
        }

        $orders = $order->order('id', 'desc')->paginate(10);

        // 处理未支付订单 item status_code
        $orders = $orders->toArray();
        if ($orders['data']) {
            $data = $orders['data'];
            foreach ($data as $key => $od) {
                $data[$key] = self::setOrderItemStatusByOrder($od);
            }

            $orders['data'] = $data;
        }

        return $orders;
    }


    // 订单详情
    public static function detail($params)
    {
        $user = User::info();
        extract($params);

        $order = (new self())->with('item')->where('user_id', $user->id);

        if (isset($order_sn)) {
            $order = $order->where('order_sn', $order_sn);
        }
        if (isset($id)) {
            $order = $order->where('id', $id);
        }

        $order = $order->find();

        if (!$order) {
            throw new Exception('订单不存在');
        }

        // 处理未支付订单 item status_code
        $order = self::setOrderItemStatusByOrder($order);

        return $order;
    }


    // 订单商品详情
    public static function itemDetail($params)
    {
        $user = User::info();
        extract($params);
        $type = $type ?? 'default';

        $order = (new self())->with(['item' => function ($query) use ($order_item_id) {
            $query->where('id', $order_item_id);
        }])->where('user_id', $user->id);

        if (isset($order_sn)) {
            $order = $order->where('order_sn', $order_sn);
        }
        if (isset($id)) {
            $order = $order->where('id', $id);
        }

        $order = $order->find();

        if (!$order || !$order->item) {
            throw new Exception('订单不存在');
        }

        // 处理未支付订单 item status_code
        $order = self::setOrderItemStatusByOrder($order);
        $item = $order['item'][0];
        unset($order['item']);  // 移除订单表中的 item
        $item['order'] = $order;        // 订单信息

        if ($type == 'dispatch') {
            $store = null;
            $verifies = [];
            if (in_array($item['dispatch_type'], ['selfetch', 'store']) && $item['store_id']) {
                // 自提，商家配送

                if ($item['dispatch_type'] == 'selfetch') {
                    // 关联核销码
                    $verifies = Verify::where('order_id', $item['order_id'])
                        ->where('order_item_id', $item['id'])->select();
                }

                $store = Store::where('id', $item['store_id'])->find();
            }
            $item['store'] = $store;        // 门店信息
            $item['verify'] = $verifies;    // 核销券列表

            // $item['autosend'] 
            // 订单详情，自动发货内容 待补充
        }

        return $item;
    }




    // 取消订单
    public static function operCancel($params)
    {
        $user = User::info();
        extract($params);

        $order = self::with('item')->where('user_id', $user->id)->where('id', $id)->nopay()->find();

        if (!$order) {
            throw new Exception('订单不存在或已取消');
        }

        // 订单取消
        $order = (new self)->doCancel($order, $user);

        return $order;
    }


    public function doCancel($order, $user, $type = 'user')
    {
        $order = Db::transaction(function () use ($order, $user, $type) {
            $data = ['order' => $order];
            \think\Hook::listen('order_cancel_before', $data);

            $order->status = Order::STATUS_CANCEL;        // 取消订单
            $order->ext = json_encode($order->setExt($order, ['cancel_time' => time()]));      // 取消时间
            $order->save();

            OrderAction::operAdd($order, null, $user, $type, ($type == 'user' ? '用户' : '管理员') . '取消订单');

            // 订单取消，退回库存，退回优惠券积分，等
            $data = ['order' => $order];
            \think\Hook::listen('order_cancel_after', $data);

            return $order;
        });

        return $order;
    }


    private static function getItem($order, $order_item_id)
    {
        if (!$order) {
            throw new Exception('当前订单不存在');
        }

        $order_item = null;
        foreach ($order->item as $item) {
            if ($item->id == $order_item_id) {
                $order_item = $item;
                break;
            }
        }

        if (!$order_item) {
            throw new Exception('订单商品不需要操作');
        }

        return $order_item;
    }


    // 确认收货订单
    public static function operConfirm($params)
    {
        $user = User::info();
        extract($params);

        $order = self::noget()->where('user_id', $user->id)->where('id', $id)->find();

        // 获取要操作的 订单 item
        $item = self::getItem($order, $order_item_id);
        if ($item->dispatch_status == OrderItem::DISPATCH_STATUS_NOSEND) {
            throw new Exception('当前订单未发货');
        }
        if ($item->dispatch_status == OrderItem::DISPATCH_STATUS_GETED) {
            throw new Exception('当前订单已收货');
        }

        $order = Db::transaction(function () use ($order, $item, $user) {
            (new self())->getedItem($order, $item, ['oper_type' => 'user']);
        });

        return $order;
    }



    // 删除订单
    public static function operDelete($params)
    {
        $user = User::info();
        extract($params);

        $order = self::canDelete()->where('user_id', $user->id)->where('id', $id)->find();

        if (!$order) {
            throw new Exception('订单不存在或不可删除');
        }

        $order = Db::transaction(function () use ($order, $user) {
            $order->delete();        // 删除订单

            OrderAction::operAdd($order, null, $user, 'user', '用户删除订单');

            return $order;
        });

        return $order;
    }



    // 申请售后 （已废弃）
    // public static function operAftersale($params)
    // {
    //     $user = User::info();
    //     extract($params);

    //     $order = self::canAftersale()->where('user_id', $user->id)->where('id', $id)->find();

    //     // 获取要操作的 订单 item
    //     $order_item = self::getItem($order, $order_item_id);
    //     if ($order_item->aftersale_status != 0) {
    //         throw new Exception('当前订单已在售后状态');
    //     }

    //     $order = Db::transaction(function () use ($order, $order_item, $user) {
    //         $order_item->aftersale_status = OrderItem::AFTERSALE_STATUS_AFTERING;        // 申请售后
    //         $order_item->save();

    //         OrderAction::operAdd($order, $order_item, $user, 'user', '用户申请售后');

    //         return $order;
    //     });

    //     return $order;
    // }


    // // 申请退款 （已废弃）
    // public static function operRefund($params)
    // {
    //     $user = User::info();
    //     extract($params);

    //     $order = self::canAftersale()->where('user_id', $user->id)->where('id', $id)->find();

    //     // 获取要操作的 订单 item
    //     $order_item = self::getItem($order, $order_item_id);
    //     if (!in_array($order_item->refund_status, [
    //         OrderItem::REFUND_STATUS_REFUSE,
    //         OrderItem::REFUND_STATUS_NOREFUND,
    //     ])) {
    //         throw new Exception('当前订单已在申请退款状态');
    //     }

    //     $order = Db::transaction(function () use ($order, $order_item, $user) {
    //         $order_item->aftersale_status = OrderItem::AFTERSALE_STATUS_AFTERING;        // 申请售后
    //         $order_item->refund_status = OrderItem::REFUND_STATUS_ING;        // 申请退款
    //         $order_item->save();

    //         OrderAction::operAdd($order, $order_item, $user, 'user', '用户申请退款');

    //         return $order;
    //     });

    //     return $order;
    // }




    // 评价
    public static function operComment($params)
    {
        $user = User::info();
        extract($params);

        $order = self::with('item')->payed()->where('user_id', $user->id)->where('id', $id)->find();

        // 获取要操作的 订单 item
        $item = self::getItem($order, $order_item_id);
        if ($item->comment_status == 1) {
            throw new Exception('当前商品已评价');
        }


        $order = Db::transaction(function () use ($order, $item, $user, $params) {
            // 订单评价前
            $data = ['order' => $order, 'item' => $item];
            \think\Hook::listen('order_comment_before', $data);     // 重新拿到更新过的订单

            extract($params);
            $images = (isset($images) && $images) ? $images : null;

            GoodsComment::create([
                'goods_id' => $item->goods_id,
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'user_id' => $user->id,
                'level' => $level,
                'content' => $content,
                'images' => $images ? implode(',', $images) : $images,
                'status' => 'show'
            ]);

            $item->comment_status = OrderItem::COMMENT_STATUS_OK;        // 评价成功
            $item->save();

            OrderAction::operAdd($order, $item, $user, 'user', '用户评价成功');

            // 订单评价后
            $data = ['order' => $order, 'item' => $item];
            \think\Hook::listen('order_comment_after', $data);

            return $order;
        });

        return $order;
    }



    // 个人中心订单数量
    public static function statusNum()
    {
        $user = User::info();

        $status_num['nopay'] = self::where('user_id', $user->id)->nopay()->count();
        $status_num['nosend'] = self::where('user_id', $user->id)->payed()->nosend()->count();
        $status_num['noget'] = self::where('user_id', $user->id)->payed()->noget()->count();
        $status_num['nocomment'] = self::where('user_id', $user->id)->payed()->nocomment()->count();
        $status_num['aftersale'] = self::where('user_id', $user->id)->payed()->aftersale()->count();

        return $status_num;
    }


    public function paymentProcess($order, $notify)
    {
        $order->status = Order::STATUS_PAYED;
        $order->paytime = time();
        $order->transaction_id = $notify['transaction_id'];
        $order->payment_json = $notify['payment_json'];
        $order->pay_type = $notify['pay_type'];
        $order->pay_fee = $notify['pay_fee'];
        $order->save();

        $user = User::where('id', $order->user_id)->find();
        OrderAction::operAdd($order, null, $user, 'user', '用户支付成功');

        // 支付成功后续使用异步队列处理
        \think\Queue::push('\addons\shopro\job\OrderPayed@payed', ['order' => $order, 'user' => $user], 'shopro-high');
        return $order;
    }


    // 开始退款
    public static function startRefund($order, $item, $refund_money, $user = null, $remark = '')
    {
        // 订单退款前
        $data = ['order' => $order, 'item' => $item];
        \think\Hook::listen('order_refund_before', $data);

        $item->refund_status = \app\admin\model\shopro\order\OrderItem::REFUND_STATUS_OK;    // 同意退款
        $item->refund_fee = $refund_money;
        $item->save();

        \addons\shopro\model\OrderAction::operAdd($order, $item, $user, ($user ? 'admin' : 'system'), $remark . '，退款金额：' . $refund_money);

        \app\admin\model\shopro\order\Order::refund($order, $item, $refund_money, $remark);

        // 订单退款后
        $data = ['order' => $order, 'item' => $item];
        \think\Hook::listen('order_refund_after', $data);
    }


    // 退款
    public static function refund($order, $item, $refund_money, $remark = '')
    {
        // 生成退款单
        $refundLog = new RefundLog();
        $refundLog->order_sn = $order->order_sn;
        $refundLog->refund_sn = RefundLog::getSn($order->user_id);
        $refundLog->order_item_id = $item->id;
        $refundLog->pay_fee = $order->pay_fee;
        $refundLog->refund_fee = $refund_money;
        $refundLog->pay_type = $order->pay_type;
        $refundLog->save();

        if ($order->pay_type == 'wechat' || $order->pay_type == 'alipay') {
            // 微信|支付宝退款

            // 退款数据
            $order_data = [
                'out_trade_no' => $order->order_sn
            ];

            if ($order->pay_type == 'wechat') {
                $total_fee = $order->pay_fee * 100;
                $refund_fee = $refund_money * 100;

                $order_data = array_merge($order_data, [
                    'out_refund_no' => $refundLog->refund_sn,
                    'total_fee' => $total_fee,
                    'refund_fee' => $refund_fee,
                    'refund_desc' => $remark,
                ]);
            } else {
                $order_data = array_merge($order_data, [
                    'out_request_no' => $refundLog->refund_sn,
                    'refund_amount' => $refund_money,
                ]);
            }

            $notify_url = request()->domain() . '/addons/shopro/pay/notifyr/payment/' . $order->pay_type . '/platform/' . $order->platform;

            $pay = new \addons\shopro\library\PayService($order->pay_type, $order->platform, $notify_url);
            $result = $pay->refund($order_data);

            \think\Log::write('refund-result' . json_encode($result));


            if ($order->pay_type == 'wechat') {
                // 微信通知回调 pay->notifyr
                if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
                    return true;
                } else {
                    throw new \Exception($result['return_msg']);
                }
            } else {
                // 支付宝通知回调 pay->notifyx
                if ($result['code'] == "10000") {
                    return true;
                } else {
                    throw new \Exception($result['msg']);
                }
            }

            // {        // 微信返回结果
            //     "return_code":"SUCCESS",
            //     "return_msg":"OK",
            //     "appid":"wx39cd0799d4567dd0",
            //     "mch_id":"1481069012",
            //     "nonce_str":"huW9eIAb5BDPn0Ma",
            //     "sign":"250316740B263FE53F5DFF50AF5A8FA1",
            //     "result_code":"SUCCESS",
            //     "transaction_id":"4200000497202004072822298902",
            //     "out_trade_no":"202010300857029180027000",
            //     "out_refund_no":"1586241595",
            //     "refund_id":"50300603862020040700031444448",
            //     "refund_channel":[],
            //     "refund_fee":"1",
            //     "coupon_refund_fee":"0",
            //     "total_fee":"1",
            //     "cash_fee":"1",
            //     "coupon_refund_count":"0",
            //     "cash_refund_fee":"1
            // }

            // {        // 支付宝返回结果
            //     "code": "10000",
            //     "msg": "Success",
            //     "buyer_logon_id": "157***@163.com",
            //     "buyer_user_id": "2088902485164146",
            //     "fund_change": "Y",
            //     "gmt_refund_pay": "2020-08-15 16:11:45",
            //     "out_trade_no": "202002460317545607015300",
            //     "refund_fee": "0.01",
            //     "send_back_fee": "0.00",
            //     "trade_no": "2020081522001464141438570535"
            // }
        } else if ($order->pay_type == 'wallet') {
            // 余额退款
            \addons\shopro\model\User::moneyAdd($order->user_id, $refund_money, 'wallet_refund', $order->id, [
                'order_id' => $order->id,
                'order_sn' => $order->order_sn,
                'item_id' => $item->id,
            ]);

            self::refundFinish($order, $item, $refundLog);

            return true;
        } else if ($order->pay_type == 'score') {
            // 积分退款，暂不支持积分退款
        }
    }

    public static function refundFinish($order, $item, $refundLog)
    {
        // 退款完成
        $refundLog->status = 1;
        $refundLog->save();

        // 退款完成
        $item->refund_status = \app\admin\model\shopro\order\OrderItem::REFUND_STATUS_FINISH;    // 退款完成
        $item->save();
        \addons\shopro\model\OrderAction::operAdd($order, $item, null, 'admin', '退款成功');
    }



    public function setExt($order, $field, $origin = [])
    {
        $newExt = array_merge($origin, $field);

        $orderExt = $order['ext_arr'];

        return array_merge($orderExt, $newExt);
    }
}
