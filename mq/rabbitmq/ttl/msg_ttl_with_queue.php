<?php 
require __DIR__ . '/../../../vendor/autoload.php';

use PhpAmqpLib\Wire\AMQPTable;
use PhpAmqpLib\Message\AMQPMessage;
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

// 通过队列属性设置消息过期时间为10s, 然后在管理页面查看10s之后消息是否消失
$arguments = new AMQPTable();
$arguments->set("x-message-ttl", 10000);

$queueName = 'test_msg_ttl';

$channel->queue_declare($queueName, false, true, false, false, false, $arguments);

$message = new AMQPMessage('Hello RabbitMQ');

$channel->basic_publish($message, '', $queueName);

$channel->close();
$connection->close();