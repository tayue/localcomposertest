<?php
$dbtest1 = new mysqli("192.168.99.88","root","root","test1")or die("test1 连接失败");
$dbtest2   = new mysqli("localhost","root","root","test")or die("test 连接失败");


$username = '温柔的风';
$order_money = 100;

//为XA事务指定一个id，xid 必须是一个唯一值。
$xid = uniqid("xa");


//两个库指定同一个事务id，表明这两个库的操作处于同一事务中
$dbtest1->query("XA START '$xid'");//准备事务1
$dbtest2->query("XA START '$xid'");//准备事务2
try {
    //$dbtest1
    $sql = "INSERT INTO t_user_orders (`username`,`money`) VALUES ('{$username}',$order_money)";
    $return = $dbtest1->query($sql) ;
    if($return == false) {
        throw new Exception("库dbtest1@192.168.99.88 执行 t_user_orders 操作失败！");
    }

    $sql = "update t_user_account set money=money-".$order_money." where username='$username'";
    //$dbtest2
    $return = $dbtest2->query($sql) ;
    if($return == false) {
        throw new Exception("库dbtest2@localhost 执行 update t_user_account 操作失败！");
    }

    //阶段1：$dbtest1提交准备就绪
    $dbtest1->query("XA END '$xid'");
    $dbtest1->query("XA PREPARE '$xid'");
    //阶段1：$dbtest2提交准备就绪
    $dbtest2->query("XA END '$xid'");
    $dbtest2->query("XA PREPARE '$xid'");
    //阶段2：提交两个库
    $dbtest1->query("XA COMMIT '$xid'");
    $dbtest2->query("XA COMMIT '$xid'");
} catch (Exception $e) {
    //阶段2：回滚
    $dbtest1->query("XA ROLLBACK '$xid'");
    $dbtest2->query("XA ROLLBACK '$xid'");
    die($e->getMessage());
}
$dbtest1->close();
$dbtest2->close();
