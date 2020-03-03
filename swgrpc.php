<?php
/**
 * Created by PhpStorm.
 * User: hdeng
 * Date: 2020/1/19
 * Time: 17:21
 */
require './vendor/autoload.php';

$http = new \Swoole\Http\Server('0.0.0.0', 9501);
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
        $response_message = new HelloReply();
        $response_message->setMessage('Hello ' . $request_message->getName());
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
