<?php
/**
 * Created by PhpStorm.
 * User: hdeng
 * Date: 2020/1/20
    grpc = http2 + protobuf
    grpc 基于 http2 协议进行通信, 理解上面的基础知识, 再来看 grpc 使用的 http2 协议通信细节, 完全可以简单实现:
 */
ini_set("display_errors", "On");//打开错误提示
ini_set("error_reporting", E_ALL);//显示所有错误
include_once "./vendor/autoload.php";
use Grpc\HelloRequest;
use Grpc\HelloResponse;
$http = new \Swoole\Http\Server('0.0.0.0', 9502);
$http->set([
    'open_http2_protocol' => true,
]);
$http->on('workerStart', function (\Swoole\Http\Server $server) {
    echo "workerStart \n";
});
$http->on('request', function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) {
    // request_uri 和 proto 文件中 rpc 对应关系: /{package}.{service}/{rpc}
    $path = $request->server['request_uri'];
    if ($path == '/grpc.HelloService/SayHello') {
        // decode, 获取 rpc 中的请求
        $request_message = \Grpc\Parser::deserializeMessage([HelloRequest::class, null], $request->rawContent());
        // encode, 返回 rpc 中的应答
        $response_message = new HelloResponse();
        $response_message->setReply('Hello ' . $request_message->getGreeting());
        $response->header('content-type', 'application/grpc');
        $response->header('trailer', 'grpc-status, grpc-message');
        $trailer = [
            "grpc-status" => "0",
            "grpc-message" => ""
        ];
        foreach ($trailer as $trailer_name => $trailer_value) {
            $response->trailer($trailer_name, $trailer_value);
        }
        $response->end(\Grpc\Parser::serializeMessage($response_message));
    }
});

$http->start();
