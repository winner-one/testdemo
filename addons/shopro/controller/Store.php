<?php

namespace addons\shopro\controller;

use addons\shopro\exception\Exception;
use addons\shopro\model\Store as ModelStore;
use addons\shopro\model\User;
use addons\shopro\model\UserStore;
use think\Db;

class Store extends Base
{

    protected $noNeedLogin = ['*'];
    // protected $noNeedLogin = ['lists', 'idsList', 'detail'];
    protected $noNeedRight = ['*'];


    public function index()
    {
        // if ($this->request->get('ids')) {
        //     $this->success('获取门店列表', ModelStore::where('id', 'in', explode(',', $this->request->get('ids')))->select());
        // }
        $user = User::info();
        $userStore = UserStore::where('user_id', $user->id)->select();
        $store_id_arr = array_column($userStore, 'store_id');

        $stores = [];
        if ($store_id_arr) {
            $stores = ModelStore::where('id', 'in', $store_id_arr)->select();
        }

        $this->success('获取门店列表', $stores);
    }

    public function idsList()
    {
        $params = $this->request->get();
        $data = ModelStore::getStoreList($params);
        $this->success('门店列表', $data);
    }

    public function lists()
    {
        $params = $this->request->get();

        // $params['longitude'] = '113.06269';
        // $params['latitude'] = '23.69795';
        $where = [];
        if (isset($params['store_type'])) {
            if ($params['store_type'] != '999') {
                $where['store_type'] = $params['store_type'];
            }
        }
        $data = ModelStore::where($where);
        if (!empty($params['latitude'])) {
            $data->field("* , (6378.138 * 2 * asin(sqrt(pow(sin((latitude * pi() / 180 - " . $params['latitude'] . " * pi() / 180) / 2),2) + cos(latitude * pi() / 180) * cos(" . $params['latitude'] . " * pi() / 180) * pow(sin((longitude * pi() / 180 - " . $params['longitude'] . " * pi() / 180) / 2),2))) * 1000) as distance");
            if ($params['current'] == '1') {
                $data->order('distance asc');
            }
        }
        $data = $data->paginate(10, false, ['page' => $params['current_page']]);
        // $data = $data->select();

        $this->success('列表', $data);
    }

    public function detail()
    {
        $data = ModelStore::where('id', $this->request->get('id'))->find();
        // $data['shopList'] = \addons\shopro\model\Goods::alias('a')
        //     ->join('shopro_dispatch c', 'a.dispatch_ids = c.id', 'LEFT')
        //     ->join('shopro_dispatch_selfetch b', 'c.type_ids = b.id', 'LEFT')
        //     ->where('a.type', 'virtual')
        //     ->where('b.store_ids', $this->request->get('id'))
        //     ->field(['a.*', 'b.store_ids'])
        //     ->select();       

        $data['shopList'] = \addons\shopro\model\Goods::where('store_id', $this->request->get('id'))->select();

        $arr = [];
        $str = '';
        foreach ($data['openweeks_arr'] as $key => $value) {
            $weekarray = ["", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"];
            // echo $weekarray[$value];
            $str .= $weekarray[$value] . '、';
            array_push($arr, $weekarray[$value]);
        }
        $data['yinye'] = 1;
        $data['openweeks_arrs'] = $arr;
        $data['openweeks_str'] = substr($str, 0, strlen($str) - 3);

        $this->success('商品列表', $data);
    }

    public function storeShop()
    {
        // $data = Db::name('shopro_goods')->alias('a')
        //     ->join('shopro_dispatch_selfetch b', 'b.id= a.dispatch_ids')->where('a.dispatch_type', 'selfetch')->where('b.store_ids', $this->request->get('id'))->select();
        // $this->success('门店虚拟产品信息', $data);
    }
}
