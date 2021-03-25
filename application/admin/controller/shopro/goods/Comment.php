<?php

namespace app\admin\controller\shopro\goods;

use app\common\controller\Backend;

/**
 * 商品评价
 *
 * @icon fa fa-circle-o
 */
class Comment extends Backend
{

    /**
     * GoodsComment模型对象
     * @var \app\admin\model\shopro\goods\Comment
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\shopro\goods\Comment;
        $this->view->assign("statusList", $this->model->getStatusList());
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            if ($this->auth->store_id) {
                $wheres['shoprogoods.store_id'] = $this->auth->store_id;
            } else {
                $wheres = [];
            }
            $total = $this->model
                ->with(['shoprogoods', 'user', 'admin'])
                ->where($wheres)
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with(['shoprogoods', 'user', 'admin'])
                ->where($wheres)
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            foreach ($list as $row) {
                $row->getRelation('shoprogoods')->visible(['id', 'title']);
                $row->getRelation('user')->visible(['id', 'nickname']);
                $row->getRelation('admin')->visible(['id', 'nickname']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
}
