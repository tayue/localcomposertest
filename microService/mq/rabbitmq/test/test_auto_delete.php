<?php 
require __DIR__ . '/../../../vendor/autoload.php';

use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * 测试 RabbitMQ 中 auto_delete 参数的作用
 * 
 * 结论：
 */

// todo 换成自己的配置
$host = '192.168.99.88';
$port = 5672;
$username = 'admin';
$password = 'admin';
$vhost = 'my_vhost';

try {
    $connnection = new AMQPStreamConnection($host, $port, $username, $password, $vhost);

    $channel = $connnection->channel();

    $channel->exchange_declare('test_exchange', AMQPExchangeType::DIRECT, false, true, true);
    $channel->queue_declare('test_queue', false, true, false, false);
} catch(\Exception $e) {
    echo $e->getMessage();
}



