<?php
/**
 * Created by PhpStorm.
 * User: hdeng
 * Date: 2019/6/14
 * Time: 16:08
 */
$redis = new \Redis();
$users=range(1,10);
$randIndex=array_rand($users,1);
$userId=$users[$randIndex];
if ($redis->connect('127.0.0.1','6379') == false) {
    die($redis->getLastError());
}

//判断用户是否已经抢购
if($redis->hexists("mywatchlist","user_id_".$userId)==1){
    exit("已经抢购");
}
//带参数的url
if($redis->get("token")!=$_POST['token']){
    exit("参数错误");
}

$redis->watch("mywatchkey");//命令用于监视一个(或多个) key ，如果在事务执行之前这个(或这些) key 被其他命令所改动，那么事务将被打断
$mywatchkey=$redis->get("mywatchkey"); //(mywatchkey 待抢购的商品id编号key)可以理解为当前抢购的商品数量
$limit=10; //本次秒杀活动该商品的实际库存数量
if($mywatchkey>=$limit){
    exit("活动结束");
}
$redis->multi();//事务块内的多条命令会按照先后顺序被放进一个队列当中，最后由 EXEC 命令原子性(atomic)地执行。
$redis->set("mywatchkey",$mywatchkey+1);
//sleep(5);//测试watch
$rob_result = $redis->exec();//按命令执行的先后顺序排列。 当操作被打断时，返回空值 nil,在php中成功返回array(0->1)失败返回空

if($rob_result){
//保证库存，原子判断，确保当两个客户同时访问 Redis 服务器得到的是更新后的值
    if($redis->incr("stock")>$limit){
        echo "抢购失败,请重试";exit;
    }
    echo "抢购成功";
//抢购成功
    $redis->hSet("mywatchlist","user_id_".$userId,time());
    $redis->LPUSH("success",$userId);
}else{
    echo "抢购失败,请重试";
}
exit;
