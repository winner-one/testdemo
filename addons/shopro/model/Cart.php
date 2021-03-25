<?php

namespace addons\shopro\model;

use think\Model;

/**
 * 购物车模型
 */
class Cart extends Model
{

    // 表名,不含前缀
    protected $name = 'shopro_cart';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    protected $hidden = ['createtime', 'updatetime', 'deletetime'];
    //列表动态隐藏字段
//    protected static $listHidden = ['content', 'params', 'images', 'service_ids'];

    // 追加属性
    protected $append = [
    ];

    public static function info()
    {
        $user = User::info();
        $cartData = self::with('goods,sku_price')->where([
            'user_id' => $user->id
        ])->select();
        return $cartData;
    }

    public static function add($goodsList)
    {

        $user = User::info();

        foreach ($goodsList as $v) {
            $where = [
                'user_id' => $user->id,
                'goods_id' => $v['goods_id'],
                'sku_price_id' => $v['sku_price_id'],
                'deletetime' => null
            ];
            $cart = self::get($where);
            if ($cart) {
                $cart->goods_num += $v['goods_num'];
                $cart->save();
            }else{
                $cartData = [
                    'user_id' => $user->id,
                    'goods_id' => $v['goods_id'],
                    'goods_num' => $v['goods_num'],
                    'sku_price_id' => $v['sku_price_id']
                ];
                $cart = self::create($cartData);
            }

        }

        return $cart;


    }

    public static function edit($params)
    {
        extract($params);
        $user = User::info();
        $where['user_id'] = $user->id;
        switch ($act) {
            case 'delete':
                foreach ($cart_list as $v) {
                    $where['id'] = $v;
                    self::where($where)->delete();
                }
                break;
            case 'change':
                foreach ($cart_list as $v) {
                    $where['id'] = $v;
                    self::where($where)->update(['goods_num' => $value]);
                }
                break;
        }

        return true;


    }

    public function goods()
    {
        return $this->hasOne(Goods::class, 'id', 'goods_id');
    }

    public function skuPrice()
    {
        return $this->hasOne(GoodsSkuPrice::class, 'id', 'sku_price_id');
    }
    

}
