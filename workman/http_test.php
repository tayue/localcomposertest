<?php
require '../vendor/autoload.php';

use Workerman\Worker;


// 创建一个Worker监听2345端口，使用http协议通讯
$http_worker = new Worker("http://0.0.0.0:2345");

// 启动4个进程对外提供服务
$http_worker->count = 4;

$http_worker->onError = function($connection, $code, $msg)
{
    echo "error $code $msg\n";
};

$http_worker->onBufferFull = function($connection)
{
    echo "bufferFull and do not send again\n";
};

$http_worker->onBufferDrain = function($connection)
{
    echo "buffer drain and continue send\n";
};


$http_worker->onClose = function($connection)
{
    echo "connection closed\n";
};

$http_worker->onConnect = function($connection)
{
    echo "new connection from ip " . $connection->getRemoteIp() . "\n";
};

// 接收到浏览器发送的数据时回复hello world给浏览器
$http_worker->onMessage = function($connection, $data)
{
    print_r($data);
    // 向浏览器发送hello world
    $connection->send('hello world');
};

$http_worker->onWorkerStart = function($worker)
{
    echo "Worker starting...\n";
};

// 运行worker
Worker::runAll();