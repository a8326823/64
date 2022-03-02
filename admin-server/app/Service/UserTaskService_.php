<?php

declare (strict_types=1);
/**
 * @copyright 深圳市易果网络科技有限公司
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service;

use App\Controller\AbstractController;
use App\Kernel\Utils\JwtInstance;
use App\Model\User;
use App\Model\UserTask;
use App\Service\DAO\MemberDAO;
use App\Service\DAO\UserBillDAO;
use App\Service\DAO\UserCreditRecordDAO;
use App\Service\DAO\UserLevelDAO;
use App\Service\DAO\UserMemberDAO;
use App\Service\DAO\UserTaskDAO;
use App\Service\DAO\TaskDAO;

use Hyperf\Cache\Annotation\CacheEvict;
use Hyperf\DbConnection\Db;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * 用户任务服务
 *
 * @author 李想
 * @package App\Service
 */
class UserTaskService extends AbstractController
{
    /**
     * 审核任务
     *
     * @param array $params
     * @throws InvalidArgumentException
     */
    public function audit(array $params)
    {
        // 检查用户任务记录
        $user_task = $this->container->get(UserTaskDAO::class)->findById((int)$params['id']);
        if (!$user_task) {
            $this->error('logic.USER_TASK_NOT_FOUND');
        }
        

        // 判断用户任务状态
        if ($user_task->status !== 1 || !empty($user_task->audit_time)) {
            $this->error('logic.USER_TASK_IS_NOT_TO_BE_REVIEWED');
        }

        // 判断用户是否存在
        if (!$user = $this->container->get(MemberDAO::class)->findUserById($user_task->user_id)) {
            $this->error('logic.USER_NOT_FOUND');
        }


        // 审核通过
        if ((int)$params['status'] === 2) {
            $this->auditPass($user_task, $user, $params['remark'] ?? '', JwtInstance::instance()->build()->getId());
        } else {
            $this->auditRefuse($user_task, $user, $params['remark'] ?? '');
        }

        // 清除缓存
        //        $this->eventDispatcher->dispatch(new DeleteListenerEvent('user_received_ids_update', [$user->id]));
        $this->cache->delete('user_received_ids:' . $user->id);
    }

    /**
     * 审核通过
     *
     *
     * @CacheEvict(prefix="UserById", value="#{user.id}")
     * @param UserTask $user_task
     * @param User $user
     * @param string $remark
     */
    public function auditPass(UserTask $user_task, User $user, string $remark, int $admin_id)
    {
        Db::beginTransaction();
        try {
            // 如果是今天第一次完成任务 加一点信用分
            if ($this->container->get(UserTaskDAO::class)->getUserTodayCompleteCount($user->id) === 0) {
                // 创建信用分记录
                $this->container->get(UserCreditRecordDAO::class)->create([
                    'user_id' => $user->id,
                    'type'    => 2,
                    'credit'  => 1
                ]);

                // 增加信用分
                $user->increment('credit', 1);
            }

            // 修改用户任务记录状态
            $user_task->status     = 2;
            $user_task->admin_id   = $admin_id;
            $user_task->remark     = $remark;
            $user_task->audit_time = time();
            $user_task->save();

            // 任务返利
            
            $this->addTaskRebate($user_task, $user);
            
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->error('logic.SERVER_ERROR');
        }
    }

    /**
     * 审核拒绝
     *
     * @param UserTask $user_task
     * @param User $user
     * @param string $remark
     */
    public function auditRefuse(UserTask $user_task, User $user, string $remark)
    {
        Db::beginTransaction();
        try {
            // 扣除信用分
            $this->container->get(UserCreditRecordDAO::class)->create([
                'user_id' => $user->id,
                'type'    => 3,
                'credit'  => -2
            ]);
            $user->decrement('credit', 2);

            // 修改任务状态
            $user_task->status     = 3;
            $user_task->admin_id   = JwtInstance::instance()->build()->getId();
            $user_task->remark     = $remark;
            $user_task->audit_time = time();
            $user_task->save();

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->error($e->getMessage());
        }
    }

    /**
     * 任务返利
     *
     * @param UserTask $user_task
     * @param User $user
     */
    public function addTaskRebate(UserTask $user_task, User $user)
    {
        // 任务收入 创建用户账单
        $this->container->get(UserBillDAO::class)->create([
            'user_id'        => $user->id,
            'type'           => 1,
            'balance'        => $user_task->amount,
            'before_balance' => $user->balance,
            'after_balance'  => $user->balance + $user_task->amount,
        ]);
        // 增加用户余额
        $user->increment('balance', $user_task->amount);

        // 获取会员等级列表
        $level_list = $this->container->get(UserLevelDAO::class)->get(['rebate_type' => 2]);
        $level_list = array_column($level_list->toArray(), null, 'level');

        $type = 3;
    
        //获取任务信息
        $task = $this->container->get(TaskDAO::class)->findById($user_task->task_id);
        $task_level = $task->level;
        
        // 一级返利
        $p1_user = $this->container->get(MemberDAO::class)->findUserById($user->parent_id);
        if ($p1_user) {
            $p1_user_max_level = $this->container->get(UserMemberDAO::class)->getUserMaxLevel($user->parent_id);
           
            //echo $p1_user_max_level.' '.$task_level.' '.$level_list[$p1_user_max_level]['task_val'];
            if($p1_user_max_level>=$task_level){
                $p1_task_rebate = $user_task->amount * $level_list[$task_level]['task_level_rebate']['p_one_rebate'] ?? 0;
            }else{
                $p1_task_rebate = $level_list[$p1_user_max_level]['task_val'] * $level_list[$p1_user_max_level]['task_level_rebate']['p_one_rebate'] ?? 0;
            }
           

            $this->container->get(UserBillDAO::class)->create([
                'user_id'        => $p1_user->id,
                'type'           => 3,
                'balance'        => $p1_task_rebate,
                'before_balance' => $p1_user->balance,
                'after_balance'  => $p1_user->balance + $p1_task_rebate,
                'low_id'         => $user->id
            ]);
            
            $p1_user->increment('balance', $p1_task_rebate );
            
        }

        // 二级返利
        
        $p2_user = $this->container->get(MemberDAO::class)->findUserById($p1_user->parent_id ?? 0);
        if ($p2_user ) {
            
            $p2_user_max_level = $this->container->get(UserMemberDAO::class)->getUserMaxLevel($p1_user->parent_id);
          
            if($p2_user_max_level>=$task_level){
                $p2_task_rebate = $user_task->amount * $level_list[$task_level]['task_level_rebate']['p_two_rebate'] ?? 0;
            }else{
                $p2_task_rebate = $level_list[$p2_user_max_level]['task_val'] * $level_list[$p2_user_max_level]['task_level_rebate']['p_two_rebate'] ?? 0;
            }
           
            $this->container->get(UserBillDAO::class)->create([
                'user_id'        => $p2_user->id,
                'type'           => 3,
                'balance'        => $p2_task_rebate,
                'before_balance' => $p2_user->balance,
                'after_balance'  => $p2_user->balance + $p2_task_rebate,
                'low_id'         => $user->id
            ]);

            $p2_user->increment('balance', $p2_task_rebate);
        }

        // 三级返利
        $p3_user = $this->container->get(MemberDAO::class)->findUserById($p2_user->parent_id ?? 0);
        if ($p3_user) {
            $p3_user_max_level = $this->container->get(UserMemberDAO::class)->getUserMaxLevel($p2_user->parent_id);
            if($p3_user_max_level>=$task_level){
                $p3_task_rebate = $user_task->amount * $level_list[$task_level]['task_level_rebate']['p_three_rebate'] ?? 0;
            }else{
                if(isset($level_list[$p3_user_max_level])){
                     $p3_task_rebate = $level_list[$p3_user_max_level]['task_val'] ?? 0 * $level_list[$p3_user_max_level]['task_level_rebate']['p_three_rebate'] ?? 0;
            
                }else{
                    $p3_task_rebate = 0;
                }
               
                
            }
            

            $this->container->get(UserBillDAO::class)->create([
                'user_id'        => $p3_user->id,
                'type'           => 3,
                'balance'        => $p3_task_rebate,
                'before_balance' => $p3_user->balance,
                'after_balance'  => $p3_user->balance + $p3_task_rebate,
                'low_id'         => $user->id
            ]);

            $p3_user->increment('balance', $p3_task_rebate);
        }
    }

    /**
     * 一键审核通过
     */
    public function auditAllPass()
    {
        $task_number = getConfig('audit_batch_task_num', 0);

        $user_task_list = $this->container->get(UserTaskDAO::class)->getListByStatus(1, $task_number)->toArray();
        if (count($user_task_list) <= 0) {
            $this->error('logic.NO_FOUND_AUDIT_USER_TASK');
        }

        $admin_id = JwtInstance::instance()->build()->getId();

        // if ($this->cache->has('batch_lock') === true) {
        //     $this->error('logic.AUDIT_BATCH_PASS_PROCESSING');
        // } else {
        //    $this->cache->set('batch_lock', 1);
            go(function () use ($user_task_list, $admin_id) {
                foreach ($user_task_list as $user_task) {

                    $this_task = $this->container->get(UserTaskDAO::class)->findById($user_task['id']);
                    if (!$this_task) continue;
                    $this_user = $this->container->get(MemberDAO::class)->findUserById($user_task['user_id']);
                    if (!$this_user) continue;

                    try {
                        $this->auditPass($this_task, $this_user, '审核通过', $admin_id);
                    } catch (\Exception $e) {
                    }
                }

        //        $this->cache->delete('batch_lock');
            });
        //}
    }
}