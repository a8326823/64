<?php
namespace App\Task;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;

/**
 * @Crontab(name="Foo", rule="* * * * *", callback="execute", memo="这是一个示例的定时任务")
 */
class FooTask
{

    /**
     * @Inject()
     * @var \Hyperf\Contract\StdoutLoggerInterface
     */
	private $logger;

    public function execute()
    {
         $this->logger('task')->info($params);
    }
}