<?php

declare (strict_types=1);
/**
 * @copyright 深圳市易果网络科技有限公司
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Request\RechargeQrCode;

use App\Request\RequestAbstract;

/**
 * 充值二维码验证器
 *
 * @author 李想
 * @package App\Request\RechargeQrCode
 */
class QrCodeRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'image'  => 'required',
            'status' => 'required|in:0,1'
        ];
    }
}