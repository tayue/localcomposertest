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
$channel->confirm_select(); //confrim

//ack callback function
$channel->set_ack_handler(function (AMQPMessage $message){
    echo 'ack ' . $message->getBody() . PHP_EOL;
});
//nack callback function
$channel->set_nack_handler(function (AMQPMessage $message){
    echo 'nack ' . $message->getBody() .PHP_EOL;
});

try {


// 2、开启一个通道


    $exchange = 'test_exchange_confirm';
    $queue = 'test_queue_confirm';
    $route_key = 'test_confirm';

// 3、声明一个交换器，并且设置相关属性
    $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

// 4、声明一个队列, 并且设置相关属性
    $channel->queue_declare($queue, false, true, false, false);

// 5、通过路由键将交换器和队列绑定起来
    $channel->queue_bind($queue, $exchange, $route_key);

    for($i=0;$i<1;$i++){
        $body = $argv[1] ?? 'Hello RabbitMQ:'.$i;
        // 6、初始化消息，并且持久化消息
        $message = new AMQPMessage($body, [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        // 7、将消息发送到 RabbitMQ Broker
        $channel->basic_publish($message, $exchange, $route_key);
        echo $body . '  has been sent' . PHP_EOL;
        $channel->wait_for_pending_acks_returns(5);//set wait time
        sleep(1);
        if($i==10){
            //throw new Exception('rollbock');
        }
    }


} catch (Exception $e) {

    echo $e->getMessage();
}

// 8、关闭通道
$channel->close();
// 9、关闭连接
$connection->close();

