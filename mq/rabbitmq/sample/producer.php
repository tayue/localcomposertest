<?php
require __DIR__ . '/../../../vendor/autoload.php';

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Connection\AMQPStreamConnection;

// todo 换成自己的配置
$host = '192.168.99.88';
$port = 5672;
$username = 'admin';
$password = 'admin';
$vhost = 'my_vhost';
// 1、连接到 RabbitMQ Broker，建立一个连接
$connection = new AMQPStreamConnection($host, $port, $username, $password, $vhost);
$channel = $connection->channel();
try {


// 2、开启一个通道

    $channel->tx_select();//begin trx

    $exchange = 'test_exchange';
    $queue = 'test_queue';
    $route_key = 'test';

// 3、声明一个交换器，并且设置相关属性
    $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

// 4、声明一个队列, 并且设置相关属性
    $channel->queue_declare($queue, false, true, false, false);

// 5、通过路由键将交换器和队列绑定起来
    $channel->queue_bind($queue, $exchange, $route_key);

    for($i=0;$i<100;$i++){
        $body = $argv[1] ?? 'Hello RabbitMQ:'.$i;
        // 6、初始化消息，并且持久化消息
        $message = new AMQPMessage($body, [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        // 7、将消息发送到 RabbitMQ Broker
        $channel->basic_publish($message, $exchange, $route_key);
        if($i==10){
            //throw new Exception('rollbock');
        }
    }
    $channel->tx_commit();//commit trx

} catch (Exception $e) {
    $channel->tx_rollback();//rollback
    echo $e->getMessage();
}

// 8、关闭通道
$channel->close();
// 9、关闭连接
$connection->close();

