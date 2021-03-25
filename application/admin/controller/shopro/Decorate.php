<?php

namespace app\admin\controller\shopro;

use app\common\controller\Backend;
use app\admin\model\shopro\DecorateContent;
use think\Db;
use fast\Http;
use think\exception\PDOException;
use think\exception\ValidateException;
use Exception;
/**
 * 店铺装修
 *
 * @icon fa fa-circle-o
 */
class Decorate extends Backend
{

    protected $noNeedLogin = ['select'];
    /**
     * Decorate模型对象
     * @var \app\admin\model\shopro\Decorate
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\shopro\Decorate;
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function lists($type = '')
    {
        if ($this->request->isAjax()) {
            $data = $this->model->all(['type' => $type]);
            return json([
                'code' => 1,
                'msg' => '模板列表',
                'data' => $data
            ]);
        }
        return $this->view->fetch();
    }


    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post();
            //            halt($params);
            if ($params) {
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
                    $result = $this->model->allowField(true)->save($params);
                    //添加默认数据
                    if ($params['type'] === 'shop') {
                        DecorateContent::create([
                            'type' => 'user',
                            'category' => 'user',
                            'name' => '用户卡片',
                            'content' => '{"name":"用户卡片","image":"","style":1,"color":"#eeeeee"}',
                            'decorate_id' => $this->model->id
                        ]);
                    }
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
                    //添加默认模板数据
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($id = null)
    {
        $row = $this->model->get($id);
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
            $params = $this->request->post();
            if ($params) {
                $params = $this->preExcludeFields($params);
                //检查是否有同平台冲突的已发布模板
                if ($row->status === 'normal' && $row['type'] === 'shop') {
                    $platformArray = explode(',', $params['platform']);
                    $where = ['deletetime' => null, 'status' => 'normal', 'type' => 'shop', 'id' => ['neq', $id]];
                    foreach ($platformArray as $v) {
                        $publishDecorate = $this->model->where('find_in_set(:platform,platform)', ['platform' => $v])->where($where)->find();
                        if ($publishDecorate) {
                            $this->error(__($v) . ' 已经被使用');
                        }
                    }
                }

                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
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

        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 模板管理 发布
     * @param string $id
     * @param int $force
     */
    public function publish($id, $force = 0)
    {
        $row = $this->model->get($id);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        if (empty($row->platform)) {
            $this->error('请选择模板的发布平台', null, 0);
        }
        $platformArray = explode(',', $row->platform);
        $where = ['deletetime' => null, 'status' => 'normal', 'type' => 'shop'];
        $existPublish = [];
        foreach ($platformArray as $v) {
            $publishDecorate = $this->model->where('find_in_set(:platform,platform)', ['platform' => $v])->where($where)->find();
            if ($publishDecorate) {
                if ($force == 1) {
                    $platform = array_diff(explode(',', $publishDecorate->platform), [$v]);
                    $publishDecorate->platform = implode(',', $platform);
                    if ($publishDecorate->platform == '') {
                        $publishDecorate->status = 'hidden';
                    }
                    $publishDecorate->save();
                } else {
                    $existPublish[$publishDecorate->name][] = __($v);
                }
            }
        }

        if ($existPublish !== [] && $force == 0) {
            $str = '';
            foreach ($existPublish as $k => $e) {
                $str .= $k . ',';
            }
            $this->error("${str} 已存在相同的支持平台,确定替换吗?");
        }
        $row->status = 'normal';
        $row->save();
        $this->success('发布成功');
    }

    /**
     * 模板管理 下架
     * @param string $id
     */
    public function down($id)
    {
        $row = $this->model->get($id);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $where = ['deletetime' => null, 'status' => 'normal', 'type' => 'shop'];
        $publishDecorate = $this->model->where($where)->select();
        if (count($publishDecorate) == 1) {
            $this->error('您需要至少保留一个发布模板~');
        }

        $row->status = 'hidden';
        $row->save();
        $this->success('下架成功');
    }

    /**
     * 模板管理 复制
     * @param string $id
     */
    public function copy($id)
    {
        $row = $this->model->get($id);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        $this->model->save([
            'name' => "复制 {$row->name}",
            'type' => $row->type,
            'memo' => $row->memo,
            'image' => $row->image,
            'status' => 'hidden',
            'platform' => $row->platform,
        ]);
        $id = $this->model->id;
        $content = collection(DecorateContent::where('decorate_id', $row->id)
            ->order('id asc')
            ->field("type, category, content, name, $id as decorate_id")
            ->select())->toArray();

        $decorateContent = new DecorateContent();
        $decorateContent->saveAll($content);
        $this->success('复制成功');
    }


    /**
     * 自定义页面
     */
    public function custom()
    {
        return $this->view->fetch();
    }


    /**
     * 页面装修
     * @param string $id
     */
    public function dodecorate($id)
    {
        $content = new DecorateContent();
        $query = $content->where(['decorate_id' => $id]);
        if ($this->request->isPost()) {
            $params = $this->request->post("templateData");
            if ($params) {
                $params = json_decode($params, true);
                $result = false;
                Db::startTrans();
                try {
                    $decorateArray = [];
                    foreach ($params as $p => $a) {
                        foreach ($a as $c => &$o) {
                            if (isset($o['id'])) {
                                unset($o['id']);
                            }
                            $decorateArray[] = [
                                'category' => $p,
                                'content' => json_encode($o['content'], JSON_UNESCAPED_UNICODE),
                                'decorate_id' => $id,
                                'name' => $o['name'],
                                'type' => $o['type']
                            ];
                        }
                    }

                    $query->delete();
                    $result = new \app\admin\model\shopro\DecorateContent;
                    $result->saveAll($decorateArray);
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
            $this->error('请完善装修页面');
        }
        $template = $query->select();
        if ($template) {
            foreach ($template as &$t) {
                $t['content'] = json_decode($t['content'], true);
            }
        } else {
            $template = [];
        }
        $categoryArray = array_column($template, 'category');
        $templateData = [];
        foreach ($categoryArray as $k => $c) {
            $templateData[$c][] = $template[$k];
        }

        $shopro = json_decode(\app\admin\model\shopro\Config::get(['name' => 'shopro'])->value, true);
        if (empty($shopro) || empty($shopro['domain'])) {
            $this->error('请完善您的域名信息');
        } else {
            $domain = $shopro['domain'];
        }
        //        halt($template);
        $this->assignconfig('templateData', $templateData);
        $this->assignconfig('preview_id', $id);
        $this->assignconfig('iframeSrc', $domain . '/?preview_id=' . $id);
        return $this->view->fetch();
    }

    /**
     * 页面装修 保存
     * @param string $id
     */
    public function dodecorate_save($id)
    {
        if ($this->request->isPost()) {
            $decorate = $this->model->get($id);

            if (!$decorate) {
                $this->error(__('No Results were found'));
            }

            $params = $this->request->post("templateData");
            $result = $this->updateDecorateContent($id, $params);
            if ($result) {
                // 前端配置
                $url = "";
                if ($decorate->type == 'shop') {
                    extract($this->getShopro());
                    $url = "{$domain}?{$decorate->type}_id={$id}&mode=save";
                }
                $this->success('保存成功', $url, $decorate);
            } else {
                $this->error('保存失败');
            }
        }
    }

    private function updateDecorateContent($id, $params)
    {
        $result = false;

        if ($params) {
            $params = json_decode($params, true);
            Db::startTrans();
            try {
                $decorateArray = [];
                foreach ($params as $p => $a) {
                    foreach ($a as $c => &$o) {
                        if (isset($o['id'])) {
                            unset($o['id']);
                        }
                        $decorateArray[] = [
                            'category' => $p,
                            'content' => json_encode($o['content'], JSON_UNESCAPED_UNICODE),
                            'decorate_id' => $id,
                            'name' => $o['name'],
                            'type' => $o['type']
                        ];
                    }
                }

                DecorateContent::where(['decorate_id' => $id])->delete();
                $result = new DecorateContent();
                $result->saveAll($decorateArray);
                Db::commit();
                return $result;
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
        }
        return $result;
    }

    /**
     * 页面装修 预览
     */
    public function preview($id)
    {
        //前端配置
        extract($this->getShopro());

        //装修数据
        $decorate = $this->model->get($id);
        $type = $decorate['type'];
        //临时预览数据
        if ($this->request->isPost()) {
            $row = [
                'name' => "临时预览 {$decorate->name}",
                'type' => 'preview',
                'memo' => date("Y年m月d日 H:i:s", time()) . ' 创建',
                'status' => 'normal',
                'platform' => $decorate->platform
            ];
            $preview = $this->model->where('type', 'preview')->find();
            if ($preview) {
                DecorateContent::where('decorate_id', $preview->id)->delete();
                $preview->delete(true);
            }
            $this->model->save($row);
            $id = $this->model->id;
            $decorate = $this->model->getData();
            $params = $this->request->post("templateData");
            $this->updateDecorateContent($id, $params);
        }
        $previewData = array_merge($decorate, $shopro);
        $this->success($row['name'], "{$domain}?{$type}_id={$id}", $previewData);
    }

    //设计师模板
    public function designer()
    {
        $designerTemplate = Http::get('http://d.7wpp.com/api/decorate/designer');
        $res = json_decode($designerTemplate, true);
        if (isset($res['code']) && $res['code'] === 1) {
            $this->assignconfig('designerData', $res['data']);
        }
        return $this->view->fetch();
    }

    //使用设计师模板
    public function use_designer_template($id)
    {
        $decorate = Http::get('http://d.7wpp.com/api/decorate/copy?id=' . $id);
        $res = json_decode($decorate, true);
        if (isset($res['code']) && $res['code'] === 1) {
            Db::startTrans();
            try {
                $this->model->save([
                    'type' => 'shop',
                    'status' => 'hidden',
                    'image' => $res['data']['image'],
                    'memo' => $res['data']['memo'],
                    'name' => $res['data']['name'],
                    'platform' => $res['data']['platform']
                ]);
                foreach ($res['data']['content'] as &$v) {
                    $v['decorate_id'] = $this->model->id;
                    unset($v['id']);
                }
                DecorateContent::insertAll($res['data']['content']);
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
        } else {
            $this->error('模板选择错误');
        }
        $this->success('模板使用成功');
    }

    /**
     * 真实删除
     */
    public function destroy($ids = "")
    {
        $pk = $this->model->getPk();
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            $this->model->where($this->dataLimitField, 'in', $adminIds);
        }
        if ($ids) {
            $this->model->where($pk, 'in', $ids);
        }
        $count = 0;
        Db::startTrans();
        try {
            $list = $this->model->onlyTrashed()->select();
            foreach ($list as $k => $v) {
                DecorateContent::where('decorate_id', $v->id)->delete();
                $count += $v->delete(true);
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
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    public function select()
    {
        if ($this->request->isAjax()) {
            return $this->index();
        }
        return $this->view->fetch();
    }


    // 获取shopro 配置
    private function getShopro()
    {
        $shopro = json_decode(\app\admin\model\shopro\Config::get(['name' => 'shopro'])->value, true);
        if (empty($shopro) || empty($shopro['domain'])) {
            $this->error('请在商城配置中完善前端访问地址');
        } else {
            //前端域名
            $domain = $shopro['domain'];
        }

        return compact("domain", "shopro");
    }
}
