<?php

declare (strict_types=1);
/**
 * @copyright 深圳市易果网络科技有限公司
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Request\UserNotify;

use App\Request\RequestAbstract;

/**
 * 新闻验证器
 *
 * @author 李想
 * @package App\Request\UserNotify
 */
class UserNotifyRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required',
            'content' => 'required',
            'sort' => 'required|integer|gte:0'
        ];
    }
}