<?php

namespace App\Listener;

use App\Service\OrderService;
use App\Service\MessageService;
use Framework\SwServer\Coroutine\CoroutineManager;
use Framework\SwServer\Event\EventHandlerInterface;
use Framework\SwServer\Event\EventInterface;
use Framework\SwServer\Pool\RedisPoolManager;
use Swoole\Coroutine as SwCoroutine;

class ConfirmMessageConsumeListener implements EventHandlerInterface
{
    public $connectionRedis;
    public $messageService; //消息服务子系统（包含接口）
    public $orderService;

    /**
     * @param \Framework\SwServer\Event\EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        $resourceData = RedisPoolManager::getInstance()->get(5);
        if ($resourceData) {
            $this->connectionRedis = $resourceData;
            defer(function () use ($resourceData) {
                RedisPoolManager::getInstance()->put($resourceData);
            });
        }

        $this->orderService = new OrderService();
        $this->messageService = new MessageService();
        //查询消费确认超时的消息（消息恢复子系统）

        //多进程自己实现下
        $time = 1; //超时任务
        swoole_timer_tick(10000, function () use ($time) { //默认10秒钟
            go(function () use ($time) {
                //自动初始化一个Context上下文对象(协程环境下)
                $context = SwCoroutine::getContext();
                CoroutineManager::set(CoroutineManager::getInstance()->getCoroutineId(), $context);
                try {
                    echo 'Begin:[' . date('Y-m-d H:i:s') . "]:Query timeout for unacknowledged tasks\r\n";
                    //查询超时未确认的任务
                    $service = $this->connectionRedis->zRangeByScore('message_system_time', "-inf", (string)(time() - $time));
                    print_r($service);
                    foreach ($service as $v) {
                        $data = $this->connectionRedis->hget('message_system', (string)$v);
                        if (!empty($data)) {
                            $data = json_decode($data, true);
                            if ($data['status'] == 2) { //状态为2代表的是已投递，超时没有被正确消费（消息恢复系统）
                                //尝试重新投
                                //如果投递的次数超过最大值,删除任务,并且存到单独存到redis一个队列当中
                                if ($data['message_retries_number'] >= 2) {
                                    var_dump($data['message_retries_number'], "投递失败,手动重试");
                                    //可以封装成服务
                                    $this->connectionRedis->transaction(function ($redis) use ($v, $data) {
                                        $redis->hdel("message_system", (string)$v);
                                        $redis->zrem("message_system_time", (string)$v);
                                        //放在某个队列当中,在消息管理子系统当中可以手动恢复
                                        $redis->lPush("message_system_dead", json_encode($data));
                                    });
                                }
                                $this->messageService->confirmMsgToSend($v, 2); //再次投递消息业务
                            } elseif ($data['status'] == 1) { //消息状态子系统（已经进入消息子系统但是未投递的）
                                $stateJob = $this->orderService->confirmStatus($v);
                                //1.查询任务结果(主动方任务是成功的，第一次投递到被动方的服务)
                                if ($stateJob['status'] == 1) { //订单状态更新成功重新投递消息
                                    $this->messageService->confirmMsgToSend($v, 1); //投递消息业务
                                } elseif ($stateJob['status'] == 0) { //如果订单状态更新失败当前任务是失败的任务,删掉
                                    //3.任务失败（删除任务）删除消息存储
                                    $this->messageService->ackMsg($v);
                                }
                            }
                        }
                    }
                    //判断任务的状态是预发送，并且确认消息状态，如果主动方任务成功，我们就投递，否则删除
                    //确认业务状态，业务成功投递，业务失败删除
                } catch (\Exception $e) {
                    var_dump($e->getMessage());
                }
                echo 'End:[' . date('Y-m-d H:i:s') . "]:Query timeout for unacknowledged tasks\r\n";
            });

        });


    }
}