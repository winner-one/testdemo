<?php

namespace addons\shopro\model;

use think\Model;
use traits\model\SoftDelete;
/**
 * 用户收藏模型
 */
class UserFavorite extends Model
{
    use SoftDelete;

    protected $name = 'shopro_user_favorite';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';
    protected $hidden = ['createtime', 'updatetime'];

    // 追加属性
    protected $append = [
    ];

    public static function edit($params)
    {
        extract($params);
        $user = User::info();
        //批量删除模式
        if (isset($goods_ids)) {
            foreach ($goods_ids as $g) {
                self::get(['goods_id' => $g, 'user_id' => $user->id])->delete();
            }
            return false;
        }
        //单商品默认反向增删
        $favorite = self::get(['goods_id' => $goods_id, 'user_id' => $user->id]);
        if ($favorite) {
            $favorite->delete();
            return false;
        }else{
            self::create([
                'user_id' => $user->id,
                'goods_id' => $goods_id
            ]);
            return true;
        }
    }

    public static function getGoodsList()
    {
        $user = User::info();
        return self::with(['goods'])->where(['user_id' => $user->id, 'deletetime' => null])->order('createtime', 'DESC')->paginate(10);
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class, 'goods_id', 'id');
    }


}
