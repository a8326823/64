<?php

declare (strict_types=1);
/**
 * @copyright 深圳市易果网络科技有限公司
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\User;
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Database\Model\Builder;

/**
 * 用户DAO
 *
 * @author 李想
 * @package App\Service\Dao
 */
class UserDAO extends Base
{
    /**
     * 检测值是否被使用
     *
     * @param string $column
     * @param $value
     * @return bool
     */
    public function checkValueIsUsed(string $column, $value): bool
    {
        return User::query()->where($column, $value)->where('status',1)->where('type',1)->exists();
    }

    /**
     * 创建用户
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return User::query()->create($data);
    }

    /**
     * 通过账号查找用户
     *
     * @param string $account
     * @return mixed
     */
    public function findByAccount(string $account)
    {
        return User::query()->where('account', $account)->first();
    }

    /**
     * 通过ID获取用户
     *
     * @param int $id
     * @return mixed
     */
    public function getUserById(int $id): ?User
    {
        return User::query()->where('id', $id)->first();
    }

    /**
     * 获取所有用户
     *
     * @return mixed
     */
    public function getAllUsers()
    {
        return User::query()->get();
    }

    /**
     * 通过手机号获取用户
     *
     * @param string $phone
     * @return mixed
     */
    public function findByPhone(string $phone): ?User
    {
        return User::query()->where('phone', $phone)->first();
    }

    /**
     * 通过IDS获取总余额
     *
     * @param array $ids
     * @return int
     */
    public function getBalanceSumByIds(array $ids)
    {
        return User::query()->whereIn('id', $ids)->sum('balance');
    }

    public function get(array $params, array $lower_ids)
    {
        $model = User::query()->with(['userLevel:level,name', 'info'])->whereIn('id', $lower_ids);

        if (isset($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) === 2) {
            $model->whereBetween('created_at', [$params['created_at'], $params['created_at']]);
        }

        return isset($params['perPage']) ? $model->orderByDesc('created_at')->orderByDesc('id')->paginate((int)$params['perPage']) : $model->get();
    }

    public function getMemberCountByParams(array $params, array $lower_ids)
    {
        $model = User::query()->whereIn('id', $lower_ids);

        if (isset($params['time'])) {
            $model->whereBetween('created_at', $params['time']);
        }

        return $model->count();
    }

    public function getIpUserCount(string $ip)
    {
        return User::query()->where('ip', $ip)->count();
    }

    /**
     * 获取有效下级数量
     *
     * @param int $user_id
     *
     * @return int
     */
    public function getActiveSubCount(int $user_id)
    {
        return User::query()->where('parent_id', $user_id)->whereHas('recharge', function (Builder $builder) {
            $builder->where('status', 1);
        }, '>', 0)->count();
    }
}