<?php
/**
 * Created by PhpStorm.
 * User: hdeng
 * Date: 2019/6/14
 * Time: 16:15
 */

session_start();
$redis = new \Redis();
if ($redis->connect('127.0.0.1', '6379') == false) {
    die($redis->getLastError());
}


//设置token
$token = md5(rand(100, 10000));
$redis->set("token", $token);

$hl = fopen("js/nocdn.js", "w");


$js = <<<EOF
var button = '<div class="jingshan">'+
'<span id="timebox" class="start">秒杀已经开始</span>'+
'<span id="qianggou"><a href="javascript:;" ><img src="/public/images/level1_button.jpg" alt="抢购按钮"/></a></span></div>';
$(".jingshan").html(button);
 
$(function(){
var flag=1;
$("#qianggou").click(function(){
if(flag!=1){
return ;
}
flag=2;
var token='{$token}';
var url="http://local.composertest.com/qianggouserver.php"
$.ajax({
type: "POST",
url: url,
data: "token="+token,
success: function(msg){
alert( "Data Saved: " + msg );
}
});
})
 
})
EOF;
fwrite($hl, $js);
fclose($hl);
