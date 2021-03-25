<?php

namespace app\admin\controller\shopro\activity;

use app\common\controller\Backend;
use think\Db;
use think\exception\PDOException;
use think\exception\ValidateException;
use Exception;

use addons\shopro\library\traits\ActivityCache;

/**
 * 营销活动
 *
 * @icon fa fa-circle-o
 */
class Activity extends Backend
{
    use ActivityCache;
    /**
     * Activity模型对象
     * @var \app\admin\model\shopro\activity\Activity
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\shopro\activity\Activity;
        $this->assignconfig("hasRedis", $this->hasRedis());     // 检测是否配置 redis
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    /**
     * 查看活动列表
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
	    if (!class_exists(\think\Queue::class)) {
                $this->error('请安装 topthink/think-queue:v1.1.6 队列扩展');
            }
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }

            // list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $sort = $this->request->get("sort", !empty($this->model) && $this->model->getPk() ? $this->model->getPk() : 'id');
            $order = $this->request->get("order", "DESC");
            $offset = $this->request->get("offset", 0);
            $limit = $this->request->get("limit", 0);

            $total = $this->buildSearch()
                ->order($sort, $order)
                ->count();

            $list = $this->buildSearch()
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            if ($this->request->get("page_type") == 'select') {
                return json($result);
            }

            return $this->success('操作成功', null, $result);
        }
        return $this->view->fetch();
    }


    public function all() {
        if ($this->request->isAjax()) {
            $type = $this->request->get('type', 'all');

            $sort = $this->request->get("sort", !empty($this->model) && $this->model->getPk() ? $this->model->getPk() : 'id');
            $order = $this->request->get("order", "DESC");

            $activities = $this->model->withTrashed();               // 包含被删除的
            if ($type != 'all') {
                $activities = $activities->where('type', $type);
            }

            $activities = $activities
                ->field('id, title, type, starttime, endtime, rules')
                ->order($sort, $order)
                ->select();

            $activities = collection($activities)->toArray();

            return $this->success('操作成功', null, $activities);
        }
    }


    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post();
            if ($params) {
                if ($params['starttime'] > $params['endtime'] || $params['endtime'] < date('Y-m-d H:i:s')) {
                    $this->error('请设置正确的活动时间');
                }

                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    if (empty($params['type'])) {
                        $this->error('请选择营销类型');
                    }
                    $params['rules'] = json_encode($params['rules']);
                    $result = $this->model->allowField(true)->save($params);
                    $this->createOrUpdateSku($params['goods_list'], $this->model->id);

                    // 活动创建修改后
                    $data = [
                        'activity' => $this->model
                    ];
                    \think\Hook::listen('activity_update_after', $data);

                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        return $this->view->fetch();
    }

    protected function createOrUpdateSku($goodsList, $activity_id)
    {
        //如果是编辑 先下架所有的规格产品,防止丢失历史销量数据;

        \app\admin\model\shopro\activity\ActivitySkuPrice::where(['activity_id' => $activity_id])->update(['status' => 'down']);
        $list = [];
        foreach ($goodsList as $k => $g) {
            $actSkuPrice[$k] = json_decode($g['actSkuPrice'], true);

            if (!$actSkuPrice[$k]) {
                throw Exception('主规格至请完善完整规格少要有一个子规格');
            }

            foreach ($actSkuPrice[$k] as $a => $c) {
                if ($c['id'] == 0) {
                    unset($c['id']);
                }
                unset($c['sales']);
                $c['activity_id'] = $activity_id;
                $c['goods_id'] = $g['id'];
                $list[] = $c;
            }
        }
        $act = new \app\admin\model\shopro\activity\ActivitySkuPrice;
        $act->allowField(true)->saveAll($list);
    }



    /**
     * 获取详情
     */
    public function detail ($ids = null) {
        //详情
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        $goods_ids_array = explode(',', $row->goods_ids);
        $goodsList = [];
        foreach ($goods_ids_array as $k => $g) {
            $goods[$k] = \app\admin\model\shopro\goods\Goods::field('id,title,image')->where('id', $g)->find();
            $goods[$k]['actSkuPrice'] = json_encode(\app\admin\model\shopro\activity\ActivitySkuPrice::all(['goods_id' => $g, 'activity_id' => $ids]));

            $goods[$k]['opt'] = 1;
            $goodsList[] = $goods[$k];
        }

        $row->goods_list = $goodsList;

        return $this->success('获取成功', null, $row);
    }


    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        //编辑
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            if ($row->starttime < time()) {
                $this->error("活动正在进行中，不可编辑");
            }

            $params = $this->request->post();
            if ($params) {
                if ($params['starttime'] > $params['endtime'] || $params['endtime'] < date('Y-m-d H:i:s')) {
                    $this->error('请设置正确的活动时间');
                }

                $params = $this->preExcludeFields($params);

                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }

                    $params['rules'] = json_encode($params['rules']);

                    $result = $row->allowField(true)->save($params);
                    $this->createOrUpdateSku($params['goods_list'], $row->id);

                    // 活动创建修改后
                    $data = [
                        'activity' => $row
                    ];
                    \think\Hook::listen('activity_update_after', $data);

                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->assignconfig("activity", $row);
        $this->view->assign("row", $row);
        $this->assignconfig('id', $ids);
        
        return $this->view->fetch();
    }


    /**
     * 选择活动
     */
    public function select()
    {
        if ($this->request->isAjax()) {
            return $this->index();
        }
        return $this->view->fetch();
    }

    public function sku($id, $activity_id, $type = '')
    {
        $type = $type ? : 'set';
        if ($type == 'set') {
            $act = \app\admin\model\shopro\activity\Activity::where('find_in_set(:id,goods_ids)', ['id' => $id])
                ->where([
                    // 'starttime' => ['lt', time()],
                    // 'endtime' => ['gt', time()],
                    'id' => ['neq', $activity_id]
                ])->find();
            if ($act) {
                $this->error('该商品已在别的活动中参与');
            }
        }
        $skuList = \app\admin\model\shopro\goods\Sku::all(['pid' => 0, 'goods_id' => $id]);
        if ($skuList) {
            foreach ($skuList as &$s) {
                $s->children = \app\admin\model\shopro\goods\Sku::all(['pid' => $s->id, 'goods_id' => $id]);
            }
        }
        $skuPrice = \app\admin\model\shopro\goods\SkuPrice::all(['goods_id' => $id]);


        //编辑
        foreach ($skuPrice as $k => &$p) {
            $actSkuPrice[$k] = \app\admin\model\shopro\activity\ActivitySkuPrice::get(['sku_price_id' => $p['id'], 'activity_id' => $activity_id]);

            if (!$actSkuPrice[$k]) {

                $actSkuPrice[$k]['id'] = 0;
                $actSkuPrice[$k]['status'] = 'down';
                $actSkuPrice[$k]['price'] = '';
                $actSkuPrice[$k]['stock'] = '';
                $actSkuPrice[$k]['sales'] = '0';
                $actSkuPrice[$k]['sku_price_id'] = $p['id'];

            }
        }

        $this->assignconfig('skuList', $skuList);

        $this->assignconfig('skuPrice', $skuPrice);
        $this->assignconfig('actSkuPrice', $actSkuPrice);

        return $this->view->fetch();

    }


    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids) {
            $pk = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();

            $count = 0;
            Db::startTrans();
            try {
                foreach ($list as $k => $v) {
                    $count += $v->delete();

                    // 删除之后事件
                    $data = [
                        'activity' => $v
                    ];
                    \think\Hook::listen('activity_delete_after', $data);
                }
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }



    // 构建查询条件
    private function buildSearch()
    {
        $search = $this->request->get("search", '');        // 关键字
        $type = $this->request->get("type", 'all');
        $status = $this->request->get("status", 'all');

        $name = $this->model->getQuery()->getTable();
        $tableName = $name . '.';

        $activities = $this->model;

        if ($search) {
            // 模糊搜索字段
            $searcharr = ['title'];
            foreach ($searcharr as $k => &$v) {
                $v = stripos($v, ".") === false ? $tableName . $v : $v;
            }
            unset($v);
            $activities = $activities->where(function ($query) use ($searcharr, $search, $tableName) {
                $query->where(implode("|", $searcharr), "LIKE", "%{$search}%");
            });
        }

        // 活动类型
        if ($type != 'all') {
            $activities = $activities->where('type', 'like', '%' . $type . '%');
        }
        // 活动状态
        if ($status != 'all') {
            $where = [];
            if ($status == 'ing') {
                $where['starttime'] = ['<', time()];
                $where['endtime'] = ['>', time()];
            } else if ($status == 'nostart') {
                $where['starttime'] = ['>', time()];
            } else if ($status == 'ended') {
                $where['endtime'] = ['<', time()];
            }

            $activities = $activities->where($where);
        }

        return $activities;
    }

}
