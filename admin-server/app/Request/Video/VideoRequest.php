<?php

declare(strict_types=1);

namespace App\Request\Video;

use App\Request\RequestAbstract;

/**
 * @package App\Request\Video
 */
class VideoRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'video' => 'required',
            'sort' => 'required'
        ];
    }
}