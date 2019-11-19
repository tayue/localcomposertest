<?php
//初始化redis对象
$redis = new Redis();
$host="192.168.99.88";
$port=26379;
//连接sentinel服务 host为ip，port为端口，哨兵的ip和端口号
$redis->connect($host, $port);
//获取主库列表及其状态信息
$result = $redis->rawCommand('SENTINEL', 'masters');
var_dump($result);
$master_name="mymaster";
//根据所配置的主库redis名称获取对应的信息
//master_name应该由运维告知（也可以由上一步的信息中获取）
$result = $redis->rawCommand('SENTINEL', 'master', $master_name);
var_dump($result);
//根据所配置的主库redis名称获取其对应从库列表及其信息
$result = $redis->rawCommand('SENTINEL', 'slaves', $master_name);
var_dump($result);
//获取特定名称的redis主库地址
$result = $redis->rawCommand('SENTINEL', 'get-master-addr-by-name', $master_name);

var_dump($result);

list($ip,$port)=$result;
//以上部分可以获取到主库的ip和对应端口，程序可以直接像链接单台redis一样链接操作使用 


$host="192.168.99.88";

//连接sentinel服务 host为ip，port为端口，哨兵的ip和端口号
$redis->connect($host, $port);

$redis->auth("test@dbuser2018");

$redis->set("hello world",date("Y-m-d H:i:s"));

 



