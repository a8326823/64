<?php

declare (strict_types=1);
/**
 * @copyright 深圳市易果网络科技有限公司
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Controller;

use App\Service\DAO\UserCreditRecordDAO;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * 用户信用分控制器
 *
 * @Controller()
 * @author 李想
 * @package App\Controller
 */
class UserCreditController extends AbstractController
{
    /**
     * 获取用户信用分记录列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'user_id'      => 'Integer',
            'user_account' => 'String',
            'user_phone'   => 'String',
            'type'         => 'String',
            'create_time'  => 'TimestampBetween',
            'perPage'      => 'Integer'
        ], $this->request->all());

        $result = $this->container->get(UserCreditRecordDAO::class)->get($params);

        $this->success($result);
    }
}