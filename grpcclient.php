<?php
/**
 * Created by PhpStorm.
 * User: hdeng
 * Date: 2020/1/20
 * Time: 16:35
 */
use Swoole\Coroutine\Client;
include_once "./vendor/autoload.php";
use Mail\MailConfig;
$c = new Client(SWOOLE_SOCK_TCP);
$foo = new MailConfig();
$foo->setTo("George");
$foo->setFrom("John");
$foo->setMsg("Don't forget the meeting!");
$packed = $foo->serializeToString();//这里你也可以选择serializeToJsonString序列化成JSON
$response_message = new HelloRequest();
$response_message->setName('Hello');
// 协议处理
$client->set([
    'open_length_check'     => 1,
    'package_length_type'   => 'N',
    'package_length_offset' => 0,       //第N个字节是包长度的值
    'package_body_offset'   => 4,       //第几个字节开始计算长度
    'package_max_length'    => 2000000,  //协议最大长度
]);
$c->connect('127.0.0.1', '9502');
$c->send(Grpc\Parser::serializeMessage($response_message));
echo $c->recv();
$c->close();
