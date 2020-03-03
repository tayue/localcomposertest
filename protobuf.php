<?php
/**
 * Created by PhpStorm.
 * User: hdeng
 * Date: 2020/1/19
 * Time: 15:22
 */

ini_set("display_errors", "On");//打开错误提示
ini_set("error_reporting", E_ALL);//显示所有错误
require_once('./vendor/autoload.php');

use Mail\MailConfig;

use Grpc\HelloRequest;

use Grpc\Parser;

//$person = new Test\Person();
//$person->setName("lailaiji");
//$person->setAge("28");
//$person->setSex(true);
//$data = $person->serializeToString();
//file_put_contents('data.bin',$data);
$req=new HelloRequest();

$req->setGreeting("haha");



$packed=Grpc\Parser::serializeMessage($req);
echo $packed." ##\r\n";

/**
 * Writer写数据，Protobuf抽象成调用相关set函数即可
 */
$foo = new MailConfig();
$foo->setTo("George");
$foo->setFrom("John");
$foo->setMsg("Don't forget the meeting!");
$packed = $foo->serializeToString();//这里你也可以选择serializeToJsonString序列化成JSON

echo $packed;
file_put_contents("./protobuf.bin",$packed);
$bindata = file_get_contents('./protobuf.bin');
//Reader读数据，Protobuf抽象成调用相关get函数即可
$res = new MailConfig();
$res->mergeFromString($bindata);
$jsonArr = [
    "to"=> $res->getTo(),
    "from"=> $res->getFrom(),
    "msg"=> $res->getMsg(),
];
var_dump($jsonArr);

