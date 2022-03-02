<?php

declare (strict_types=1);
/**
 * @copyright 深圳市易果网络科技有限公司
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Controller;



use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 任务控制器
 *
 * @Controller()
 * @author 李想
 * @package App\Controller
 */
class FinancialProductController extends AbstractController
{
    /**
     * 获取理财产品列表
     *
     * @GetMapping(path="financialProduct")
     */
    public function getFinancialProducts(){
        $params = $this->request->all();

        $result = $this->container->get(TaskCategoryDAO::class)->get($params);

        $this->success($result);
    }
}