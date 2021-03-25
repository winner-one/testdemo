<?php

namespace app\admin\controller\shopro\user;

use app\common\controller\Backend;
use app\admin\model\shopro\user\Oauth;


/**
 * 会员管理
 *
 * @icon fa fa-user
 */
class User extends Backend
{

    protected $relationSearch = true;


    /**
     * @var \app\admin\model\user\User
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\shopro\user\User;
    }

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            $searchWhere = $this->request->request('searchWhere');
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->with('group')
                ->where($where)
                ->whereOr('user.id', '=', $searchWhere)
                ->whereOr('nickname', 'like', "%$searchWhere%")
                ->whereOr('mobile', 'like', "%$searchWhere%")
                ->order($sort, $order)
                ->count();
            $list = $this->model
                ->with('group')
                ->where($where)
                ->whereOr('user.id', '=', $searchWhere)
                ->whereOr('nickname', 'like', "%$searchWhere%")
                ->whereOr('mobile', 'like', "%$searchWhere%")
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            foreach ($list as $k => $v) {
                $v->hidden(['password', 'salt']);
                $v->third_platform = Oauth::all(['user_id' => $v->id]);
            }
            $result = array("total" => $total, "rows" => $list);

            $this->success('查看用户', null, $result);
        }
        return $this->view->fetch();
    }

    /**
     * 用户详情
     */
    public function profile($id)
    {
        $row = $this->model->get($id);
        if (!$row) {
            $this->error('未找到用户');
        }
        $row->hidden(['password','salt']);
        $row->third_platform = Oauth::all(['user_id' => $row->id]);
        if($this->request->isAjax()) {
            $this->success('用户详情', null, $row);
        }
        $this->assignconfig('row', $row);
        $this->assignconfig('groupList', \app\admin\model\UserGroup::field('id,name,status')->select());
        return $this->view->fetch();
    }

    /**
     * 更新信息
     */
    public function update()
    {
        
        $params = $this->request->post('data');
        $params = json_decode($params, true);
        $user = $this->model->get($params['id']);
        if (!$user) {
            $this->error('未找到用户');
        }
        if(!empty($params['password'])) {
            $salt = \fast\Random::alnum();
            $user->password = \app\common\library\Auth::instance()->getEncryptPassword($params['password'], $salt);
            $user->salt = $salt;
            $user->save();
        }
        $result = $user->allowField('nickname,avatar,username,group_id,birthday,bio,mobile,email,level,gender,status')->save($params);
        if($result) {
            return $this->success('更新成功', null, $user);
        }else{
            return $this->error('更新失败');
        }
    }

    /**
     * 选择
     */
    public function select()
    {
         //设置过滤方法
         $this->request->filter(['strip_tags']);
         if ($this->request->isAjax()) {
             //如果发送的来源是Selectpage，则转发到Selectpage
             if ($this->request->request('keyField')) {
                 return $this->selectpage();
             }
             list($where, $sort, $order, $offset, $limit) = $this->buildparams();
             $searchWhere = $this->request->request('searchWhere');
             $total = $this->model
                 ->where($where)
                 ->whereOr('id', '=', $searchWhere)
                 ->whereOr('nickname', 'like', "%$searchWhere%")
                 ->whereOr('mobile', 'like', "%$searchWhere%")
                 ->order($sort, $order)
                 ->field('id, nickname, mobile, avatar')
                 ->count();
             $list = $this->model
                 ->where($where)
                 ->whereOr('id', '=', $searchWhere)
                 ->whereOr('nickname', 'like', "%$searchWhere%")
                 ->whereOr('mobile', 'like', "%$searchWhere%")
                 ->order($sort, $order)
                 ->field('id, nickname, mobile, avatar')
                 ->limit($offset, $limit)
                 ->select();
             $result = array("total" => $total, "rows" => $list);
 
             $this->success('选择用户', null, $result);
         }
         return $this->view->fetch();
    }

    /**
     * 用户余额充值
     */
    public function money_recharge()
    {

        if($this->request->isAjax()) {
            $params = $this->request->post();
            $user = $this->model->get($params['user_id']);
            if (!$user) {
                $this->error(__('No Results were found'));
            }
            $res = \app\admin\model\shopro\user\User::walletChange($user, 'money', $params['money'], $params['remarks']);
            if(isset($res['result'])) {
                if(true === $res['result']) {
                    return $this->success($res['msg'], null);                    
                }else{
                    return $this->error($res['msg']);
                }
            }
        }
        return $this->view->fetch();
    }

    /**
     * 用户积分充值
     */
    public function score_recharge()
    {

        if($this->request->isAjax()) {
            $params = $this->request->post();
            $user = $this->model->get($params['user_id']);
            if (!$user) {
                $this->error(__('No Results were found'));
            }
            $res = \app\admin\model\shopro\user\User::walletChange($user, 'score', $params['score'], $params['remarks']);
            if(isset($res['result'])) {
                if(true === $res['result']) {
                    return $this->success($res['msg'], null);                    
                }else{
                    return $this->error($res['msg']);
                }
            }
        }
        return $this->view->fetch();
    }

    /**
     * 余额明细
     */
    public function money_log($user_id, $limit = 10)
    {
        if ($this->request->isAjax()) {
            $model = new \app\admin\model\shopro\user\MoneyLog;
            $data = $model->where('user_id', $user_id)->order('id desc')->paginate($limit);
            $this->success('余额明细', null, $data);
        }
    }

    /**
     * 积分明细
     */
    public function score_log($user_id, $limit = 10)
    {
        if ($this->request->isAjax()) {
            $model = new \app\admin\model\shopro\user\ScoreLog;
            $data = $model->where('user_id', $user_id)->order('id desc')->paginate($limit);
            $this->success('积分明细', null, $data);
        }
    }
    
    /**
     * 订单记录
     */
    public function order_log($user_id, $limit = 10)
    {
        if ($this->request->isAjax()) {
             $this->loadlang('shopro/order/order');
            $model = new \app\admin\model\shopro\order\Order;
            $data = $model->where('user_id', $user_id)->order('id desc')->paginate($limit);
            $this->success('订单记录', null, $data);
        }
    }
    
    /**
     * 登录记录
     */
    public function login_log($user_id, $limit = 10)
    {
        if ($this->request->isAjax()) {
        }
    }
    /**
     * 分享记录
     */
    public function share_log($user_id, $limit = 10)
    {
        if ($this->request->isAjax()) {
            $this->loadlang('shopro/share');
            $model = new \app\admin\model\shopro\Share;
            $data = $model->where('share_id', $user_id)->order('id desc')->with([
                'user' => function($query) {
                return $query->withField('id,nickname,avatar');
            }])->paginate($limit);
            foreach($data as &$v) {
                if($v['type'] === 'goods') {
                    $v['goods'] = \app\admin\model\shopro\goods\Goods::where('id', $v['type_id'])->field('id, image, title')->find();
                }
                if($v['type'] === 'groupon') {
                        $v['groupon'] = \app\admin\model\shopro\activity\Groupon::get($v['type_id']);
                }
            }
            $this->success('分享记录', null, $data);
        }
    }

    /**
     * 收藏商品
     */
    public function goods_favorite($user_id, $limit = 10)
    {
        if ($this->request->isAjax()) {
            
            $model = new \app\admin\model\shopro\user\Favorite;
            $data = $model->where('user_id', $user_id)->order('id desc')->with([
                'goods' => function($query) {
                return $query->withField('id,title,image');
            }])->paginate($limit);
            $this->success('商品收藏', null, $data);
        }
    }

    /**
     * 浏览足迹
     */
    public function goods_view($user_id, $limit = 10)
    {
        if ($this->request->isAjax()) {
            
            $model = new \app\admin\model\shopro\user\View;
            $data = $model->where('user_id', $user_id)->order('id desc')->with([
                'goods' => function($query) {
                return $query->withField('id,title,image');
            }])->paginate($limit);
            $this->success('商品收藏', null, $data);
        }
    }

    /**
     * 优惠券
     */
    public function coupon_log($user_id, $limit = 10)
    {
        if ($this->request->isAjax()) {
        $model = new \app\admin\model\shopro\user\Coupon;
        $data = $model->where('user_id', $user_id)->order('id desc')->with([ 'coupons' => function($query) {
            return $query->withField('id,name,amount');
        }])->paginate($limit);
        $this->success('优惠券', null, $data);
        }
    }
}
