<?php

namespace app\admin\controller\shopro;

use app\admin\model\shopro\user\User;
use app\common\controller\Backend;
use think\Db;

/**
 * 用户提现
 *
 * @icon fa fa-circle-o
 */
class UserWalletApply extends Backend
{
    
    /**
     * UserWalletApply模型对象
     * @var \app\admin\model\shopro\UserWalletApply
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\shopro\UserWalletApply;
        $this->view->assign("getTypeList", $this->model->getGetTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    public function applyOper($id)
    {
        $status = $this->request->post('status', -1);
        $status_msg = $this->request->post('status_msg', '');

        $apply = $this->model->get($id);
        
        if ($apply->status != 0) {
            $this->error('该提现申请已处理，不能重复处理');
        }

        $apply = Db::transaction(function () use ($apply, $status, $status_msg) {
            $apply->status = $status;
            $apply->status_msg = $status_msg;
            $apply->save();

            if ($status == -1) {
                // 把提现的金额重新加回去
                $total_money = $apply->money + $apply->charge_money;
                $bank_info = json_decode($apply, true);
                $ext = array_merge([
                    'money' => $apply->money,
                    'charge' => $apply->charge_money
                ], $bank_info);

                \addons\shopro\model\User::moneyAdd($apply->user_id, $total_money, 'cash_error', $apply->id, $ext);
            }

            // 提现结果通知
            $user = \addons\shopro\model\User::where('id', $apply['user_id'])->find();
            $user->notify(
                new \addons\shopro\notifications\Wallet([
                    'apply' => $apply,
                    'event' => 'wallet_apply'
                ])
            );

            return $apply;
        });

        return $this->success('操作成功', null, $apply);
    }
}
