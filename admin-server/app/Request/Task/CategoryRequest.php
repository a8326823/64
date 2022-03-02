<?php

declare (strict_types=1);
/**
 * @copyright 深圳市易果网络科技有限公司
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Request\Task;

use App\Request\RequestAbstract;

/**
 * 任务分类验证器
 *
 * @author 李想
 * @package App\Request\Task
 */
class CategoryRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'         => 'required',
            'icon'         => 'required',
            'banner'       => 'required',
            'lowest_price' => 'required|gt:0',
            'sort'         => 'required|between:0,999999',
            'status'       => 'required|in:0,1'
        ];
    }
}