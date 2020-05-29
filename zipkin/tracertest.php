<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require_once '../vendor/autoload.php';

use Framework\SwServer\Guzzle\ClientFactory;
use Framework\SwServer\Pool\DiPool;
use Framework\SwServer\Tracer\HttpClientFactory;
use Framework\SwServer\Tracer\TracerFactory;
use App\Middleware\TraceMiddleware;



$http = new swoole_http_server("0.0.0.0", 9502);

$http->set(['task_worker_num' => 1, 'worker_num' => 1]);

$http->on('request', function (Swoole\Http\Request $req, Swoole\Http\Response $resp) use ($http) {
    $container=DiPool::getInstance();
    $ClientFactory=new ClientFactory($container);
    $HttpClientFactory=new HttpClientFactory($ClientFactory);

    $TracerFactory=new TracerFactory($HttpClientFactory);
    $tracer=$TracerFactory->getTracer("");

    $TraceMiddleware=new TraceMiddleware($tracer);

    $TraceMiddleware->process($req);

    $resp->end("55");

});

$http->on('finish', function ()
{
    echo "task finish";
});

$http->on('task', function ($serv, $task_id, $worker_id, $data)
{

    echo "async task\n";
});

//$http->on('close', function(){
//    echo "on close\n";
//});


$http->on('workerStart', function ($serv, $id)
{
    //var_dump($serv);
});

$http->start();
