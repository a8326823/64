<?php

declare (strict_types=1);
/**
 * @copyright 深圳市易果网络科技有限公司
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service;

use App\Common\Base;
use App\Service\DAO\TaskCategoryDAO;
use App\Service\DAO\TaskDAO;
use App\Service\DAO\UserLevelDAO;

/**create
 * 任务服务
 *
 * @author 李想
 * @package App\Service
 */
class TaskService extends Base
{
    /**
     * 添加任务
     *
     * @param array $params
     */
    public function create(array $params)
    {
        // 检查任务分类
        $category = $this->container->get(TaskCategoryDAO::class)->findById((int)$params['category_id']);
        if (!$category) {
            $this->error('logic.TASK_CATEGORY_NOT_FOUND');
        }
        
        //游客不能添加任务
        if($params['level']==-1){
            $this->error('logic.LEVEL_NO_TASK');
        }

        // 检查会员等级
        $level = $this->container->get(UserLevelDAO::class)->findByLevel((int)$params['level']);
        if (!$level) {
            $this->error('logic.LEVEL_NOT_FOUND');
        }

        // 检查任务需求
        /* if (!is_array($params['description']) || count($params['description']) === 0) {
            $this->error('logic.TASK_DEMANDS_ERROR');
        } */

        // 检查价格
        if ((float)$params['amount'] < $category->lowest_price) {
            $this->formError(['amount' => __('logic.AMOUNT_GT_TASK_LOWEST_PRICE', ['amount' => (string)$category->lowest_price])]);
        }

        $this->container->get(TaskDAO::class)->create([
            'user_id'     => 0,
            'category_id' => (int)$params['category_id'],
            'level'       => (int)$params['level'],
            'title'       => trim($params['title']),
            'description' => $params['description'],
            'url'         => trim($params['url']),
            'amount'      => (float)$params['amount'],
            'num'         => (int)$params['num'],
            'sort'        => (int)$params['sort'],
            'status'      => (int)$params['status'],
            'task_type'   => (int)$params['task_type'],
        ]);
    }

    /**
     * 修改任务
     *
     * @param int $id
     * @param array $params
     */
    public function edit(int $id, array $params)
    {
        // 检查任务是否存在
        $task = $this->container->get(TaskDAO::class)->findById($id);
        if (!$task) {
            $this->error('logic.TASK_NOT_FOUND');
        }

        // 检查任务分类
        $category = $this->container->get(TaskCategoryDAO::class)->findById((int)$params['category_id']);
        if (!$category) {
            $this->error('logic.TASK_CATEGORY_NOT_FOUND');
        }

        // 检查会员等级
        $level = $this->container->get(UserLevelDAO::class)->findByLevel((int)$params['level']);
        if (!$level) {
            $this->error('logic.LEVEL_NOT_FOUND');
        }

        // 检查任务需求
        /* if (!is_array($params['description']) || count($params['description']) === 0) {
            $this->error('logic.TASK_DEMANDS_ERROR');
        } */

        if ((float)$params['amount'] < $category->lowest_price) {
            $this->formError(['amount' => __('logic.AMOUNT_GT_TASK_LOWEST_PRICE', ['amount' => (string)$category->lowest_price])]);
        }

        $this->container->get(TaskDAO::class)->edit($id, [
            'user_id'     => 0,
            'category_id' => (int)$params['category_id'],
            'level'       => (int)$params['level'],
            'title'       => trim($params['title']),
            'description' => $params['description'],
            'url'         => trim($params['url']),
            'amount'      => (float)$params['amount'],
            'num'         => (int)$params['num'],
            'sort'        => (int)$params['sort'],
            'status'      => (int)$params['status'],
            'task_type'   => (int)$params['task_type'],
        ]);
    }
}