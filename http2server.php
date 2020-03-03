<?php
include_once "./vendor/autoload.php";

use Mail\MailConfig;

$foo = new MailConfig();
$foo->setTo("George");
$foo->setFrom("John");
$foo->setMsg("Don't forget the meeting!");
$packed = $foo->serializeToString();//这里你也可以选择serializeToJsonString序列化成JSON

$key_dir = __DIR__. '/ssl';
//$http = new swoole_http_server("0.0.0.0", 9503, SWOOLE_BASE);
$http = new swoole_http_server("0.0.0.0", 9504, SWOOLE_BASE, SWOOLE_SOCK_TCP | SWOOLE_SSL);
$http->set([
    'open_http2_protocol' => 1,
    'open_length_check'     => true,
    'package_length_type'   => 'N',
    'package_length_offset' => 0,       //第N个字节是包长度的值
    'package_body_offset'   => 4,       //第几个字节开始计算长度
    'package_max_length'    => 2000000,  //协议最大长度
    'ssl_cert_file' => $key_dir.'/ssl.crt',
    'ssl_key_file' => $key_dir.'/ssl.key',
]);

$http->on('request', function (swoole_http_request $request, swoole_http_response $response) {
    $path = $request->server['request_uri'];
    $test=$request->rawContent();

    $res = new MailConfig();
    $res->mergeFromString($test);
    $jsonArr = [
        "to"=> $res->getTo(),
        "from"=> $res->getFrom(),
        "msg"=> $res->getMsg(),
    ];
    var_dump($jsonArr);

    var_dump($path,$test);
    $response->end("<html><title>test</title><body><h1>Hello world1.</h1></body></html>");
});

$http->start();
