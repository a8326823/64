<?php

declare (strict_types=1);
/**
 * @copyright 深圳市易果网络科技有限公司
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Request\UserLevel;

use App\Request\RequestAbstract;

/**
 * 用户等级验证器
 *
 * @author 李想
 * @package App\Request\UserLevel
 */
class UserLevelRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'level'                   => 'required',
            'name'                    => 'required',
            'icon'                    => 'required|max:255',
            'price'                   => 'required',
            'task_num'                => 'required|gt:0',
            'recharge_p_one_rebate'   => 'required|numeric',
            'recharge_p_two_rebate'   => 'required|numeric',
            'recharge_p_three_rebate' => 'required|numeric',
            'task_p_one_rebate'       => 'required|numeric|between:0,100|integer',
            'task_p_two_rebate'       => 'required|numeric|between:0,100|integer',
            'task_p_three_rebate'     => 'required|numeric|between:0,100|integer',
            'day'                     => 'required',
            'hour'                    => 'required',
            'minute'                  => 'required'
        ];
    }
}