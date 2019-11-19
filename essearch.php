<?php
require "./MyElasticSearch.php";
error_reporting(E_ALL);
ini_set("display_errors","on");
$es = new MyElasticSearch();

//$r = $es->delete_index();

//$r = $es->create_index();

//$r = $es->create_mappings();

echo '<pre>';
$r = $es->get_mapping();
print_r($r);

$docs = [];
$docs[] = ['id'=>1,'title'=>'苹果手机','content'=>'苹果手机，很好很强大。','price'=>1000];
$docs[] = ['id'=>2,'title'=>'华为手环','content'=>'荣耀手环，你值得拥有。','price'=>300];
$docs[] = ['id'=>3,'title'=>'小度音响','content'=>'智能生活，快乐每一天。','price'=>100];
$docs[] = ['id'=>4,'title'=>'王者荣耀','content'=>'游戏就玩王者荣耀，快乐生活，很好很强大。','price'=>998];
$docs[] = ['id'=>5,'title'=>'小汪糕点','content'=>'糕点就吃小汪，好吃看得见。','price'=>98];
$docs[] = ['id'=>6,'title'=>'小米手环3','content'=>'秒杀限量，快来。','price'=>998];
$docs[] = ['id'=>7,'title'=>'iPad','content'=>'iPad，不一样的电脑。','price'=>2998];
$docs[] = ['id'=>8,'title'=>'中华人民共和国','content'=>'中华人民共和国，伟大的国家。','price'=>19999];

foreach ($docs as $k => $v) {
    $r = $es->add_doc($v['id'],$v);
    print_r($r);
}

$r = $es->get_doc();

$r = $es->update_doc();

$r = $es->delete_doc();

$r = $es->exists_doc();


$r = $es->search_doc("手环 电脑");
print_r($r);
$r = $es->search_doc("玩");
print_r($r);
$r = $es->search_doc("中华");

print_r($r);