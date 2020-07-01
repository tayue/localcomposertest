<?php 
require __DIR__ . '/../../../../vendor/autoload.php';

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * 延迟队列测试
 * 消费死信队列 queue.delay
 */

// todo 换成自己的配置
$host = '192.168.99.88';
$port = 5672;
$username = 'admin';
$password = 'admin';
$vhost = 'my_vhost';

// 1、连接到 RabbitMQ Broker，建立一个连接
$connection = new AMQPStreamConnection($host, $port, $username, $password, $vhost);
$channel = $connection->channel();

$channel->exchange_declare('exchange.delay', AMQPExchangeType::DIRECT, false, true);
$channel->queue_declare('queue.delay', false, true, false, false);

$channel->queue_bind('queue.delay', 'exchange.delay', 'routingkey.delay');



function process_message($message)
{
    echo "开始处理订单，订单号:" . $message->body . PHP_EOL;
    // todo 获取订单的状态，如果未支付，则进行取消订单操作
    echo "获取订单的状态，如果未支付，则进行取消订单操作" . PHP_EOL;
    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
}

$channel->basic_consume('queue.delay', 'cancelOrder', false, false, false, false, 'process_message');

function shutdown($channel, $connection)
{
    $channel->close();
    $connection->close();
}
register_shutdown_function('shutdown', $channel, $connection);

while ($channel ->is_consuming()) {
    $channel->wait();
}
