<?php

declare (strict_types=1);
/**
 * @copyright 深圳市易果网络科技有限公司
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Request\Member;

use App\Request\RequestAbstract;

/**
 * 余额充值验证器
 *
 * @author 李想
 * @package App\Request\Member
 */
class BalanceRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required',
            'id' => 'required',
            'amount' => 'required|numeric'
        ];
    }
}