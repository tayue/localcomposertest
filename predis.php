<?php
require 'predis/autoload.php';//引入predis相关包  
//redis实例  
$servers = array(
    'tcp://192.168.99.88:9001',
    'tcp://192.168.99.88:9002',
    'tcp://192.168.99.88:9003',
    'tcp://192.168.99.88:9004',
    'tcp://192.168.99.88:9005',
    'tcp://192.168.99.88:9006',
);

$client = new Predis\Client($servers, array('cluster' => 'redis'));

$client->set("a", "11");
$client->set("a1", "22");
$client->set("a2", "33");
$client->set("a3", "33");
$client->set("a4", "33");
$client->set("a5", "33");
$client->set("a6", "33");
$client->set("a7", "33");

$name1 = $client->get('a');
$name2 = $client->get('a1');
$name3 = $client->get('a2');
$name4 = $client->get('a3');
$name5 = $client->get('a4');
$name6 = $client->get('a5');
$name7 = $client->get('a6');
$name8 = $client->get('a7');
var_dump($name1, $name2, $name3,$name4,$name5, $name6, $name7,$name8);die;
?>  