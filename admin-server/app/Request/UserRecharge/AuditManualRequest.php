<?php

declare (strict_types=1);
/**
 * @copyright 深圳市易果网络科技有限公司
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Request\UserRecharge;

use App\Request\RequestAbstract;

/**
 * 审核扫码充值验证器
 *
 * @author 李想
 * @package App\Request\UserRecharge
 */
class AuditManualRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id'     => 'required',
            'status' => 'required|in:1,2',
            'remark' => 'max:255'
        ];
    }
}