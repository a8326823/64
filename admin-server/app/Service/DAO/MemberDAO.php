<?php

declare (strict_types=1);
/**
 * @copyright 深圳市易果网络科技有限公司
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\User;
use App\Model\UserInfo;
use App\Model\Country;
use Hyperf\Cache\Annotation\CacheEvict;

/**
 * 用户DAO
 *
 * @author 李想
 * @package App\Service\DAO
 */
class MemberDAO extends Base
{
    /**
     * 获取用户列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = User::query()->with(['userInfo', 'country:id,name']);

        if (isset($params['id']) && $params['id'] !== '') {
            $model->where('id', (int)$params['id']);
        }

        if (isset($params['type']) && $params['type'] !== '') {
            $model->where('type', (int)$params['type']);
        }

        if (isset($params['level']) && $params['level'] !== '') {
            $model->whereHas('userMember', function ($query) use ($params) {
                if ($params['level'] == -1) {
                    return $query->where('level', $params['level']);
                } else {
                    return $query->where('level', $params['level'])->where('effective_time', '>', time());
                }
            });
        }

        if (isset($params['account']) && $params['account'] !== '') {
            $model->where('account', trim($params['account']));
        }

        if (isset($params['phone']) && $params['phone'] !== '') {
            $model->where('phone', trim($params['phone']));
        }
        
        if (isset($params['parent_id']) && $params['parent_id'] !== '') {
            $model->where('parent_id', trim($params['parent_id']));
        }
        
        if (isset($params['ip']) && $params['ip'] !== '') {
            $model->where('ip', trim($params['ip']));
        }

        if (isset($params['nickname']) && $params['nickname'] !== '') {
            $model->where('nickname', trim($params['nickname']));
        }

        if (isset($params['status']) && $params['status'] !== '') {
            $model->where('status', (int)$params['status']);
        }

        if (isset($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) === 2) {
            $model->whereBetween('created_at', [strtotime($params['created_at'][0]), strtotime($params['created_at'][1] . '23:59:59')]);
        }

        if (isset($params['search_time']) && is_array($params['search_time']) && count($params['search_time']) === 2) {
            $model->whereBetween('created_at', $params['search_time']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('created_at')->orderByDesc('id')->paginate((int)$params['perPage']) : $model->get();
    }

    public function getUsers()
    {
        return User::query()->with(['userInfo', 'country:id,name'])->orderBy('id', 'asc')->paginate(500);
    }
    
    public function getExportUsers()
    {
        return User::query()->with(['userInfo', 'country:id,name'])->orderBy('id', 'asc')->paginate(1000000);
    }

    public function getAllUsers()
    {
        return User::query()->get();
    }

    /**
     * 通过ID获取用户
     *
     * @param int $id
     * @return mixed
     */
    public function findUserById(int $id): ?User
    {
        return User::query()->where('id', $id)->first();
    }
    
    /**
     * 通过ID获取用户明细
     *
     * @param int $id
     * @return mixed
     */
    public function findUserInfoById(int $id): ?userInfo
    {
        return userInfo::query()->where('user_id', $id)->first();
    }
    
    /**
     * 通过ID获取国家
     *
     * @param int $id
     * @return mixed
     */
    public function findCountyById(int $id): ?Country
    {
        return Country::query()->where('id', $id)->first();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function first(int $id): ?User
    {
        return User::query()->with(['userInfo'])->where('id', $id)->first();
    }

    /**
     * 通过ID批量修改状态
     *
     * @CacheEvict(prefix="UserById", value="#{id}")
     * @param int $id
     * @param int $status
     * @return int
     */
    public function changeStatusById(int $id, int $status)
    {
        return User::query()->where('id', $id)->update([
            'status' => $status
        ]);
    }

    /**
     * 获取用户总数
     *
     * @return int
     */
    public function getMemberCount()
    {
        return User::query()->count();
    }

    public function getMemberCountByParams(array $params)
    {
        $model = User::query();

        if (isset($params['time'])) {
            $model->whereBetween('created_at', $params['time']);
        }

        return $model->count();
    }

    /**
     * 检测值是否被使用
     *
     * @param string $column
     * @param $value
     * @return bool
     */
    public function checkValueIsUsed(string $column, $value): bool
    {
        return User::query()->where($column, $value)->exists();
    }

    /**
     * 检测值是否被使用
     *
     * @param string $column
     * @param $value
     * @param int $user_id
     * @return bool
     */
    public function checkValueIsUsedExpectUserId(string $column, $value, int $user_id): bool
    {
        return User::query()->where('id', '<>', $user_id)->where($column, $value)->exists();
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

    public function getIpUserCount(string $ip)
    {
        return User::query()->where('ip', $ip)->count();
    }
}