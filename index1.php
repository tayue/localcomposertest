<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/22
 * Time: 9:58
 */




function systemError() {
    $message = '';
    if ($error = error_get_last()) {
        //程序报错处理，通常会跳转到用户自定义的页面，同时记录错误信息
        $separator = "\r\n";
        $message .= "错误:" . $error['message'] . $separator;
        $message .= "文件:" . $error['file'] . $separator;
        $message .= "行数:" . $error['line'] . $separator;
        $message = str_replace($separator, '<br />', $message);
        echo $message;
        exit;
    }else{
        echo "_____final________";
        //此处处理其它一些业务逻辑
    }
}

class Test {
    public static function foo() {
        echo 'Test::foo() called'.'<br/>';
    }
    public static function bar() {
        echo 'Test::bar() called'.'<br/>';
    }
    public static function msg($msg) {
        echo "$msg".'<br/>';
    }
}

$queue = new SplQueue();
$queue->enqueue("a");
$queue->enqueue("b");
$queue->enqueue("c");
$res=$queue->dequeue();
$queue->enqueue($res);
$queue->enqueue("d");
$res1=$queue->dequeue();
$queue->enqueue($res1);
$queue->enqueue("e");

var_dump($res,$res1);


echo nl2br($queue->count()."___\r\n");
foreach ($queue as $task) {
    echo nl2br($task."\r\n");
}

class My extends Thread{
    function run(){
        for($i=1;$i<10;$i++){
            echo Thread::getCurrentThreadId() .  "\n";
            sleep(2);     // <------
        }
    }
}

for($i=0;$i<2;$i++){
    $pool[] = new My();
}

foreach($pool as $worker){
    $worker->start();
}
foreach($pool as $worker){
    $worker->join();
}

//$queue->setIteratorMode(SplQueue::IT_MODE_DELETE);
//$queue->enqueue(array("Test", "foo"));
//$queue->enqueue(array("Test", "bar"));
//$queue->enqueue(array("Test", "msg", "Hi there!"));
//
//echo $queue->count()."___\r\n";
////while($task=$queue->dequeue()){
////    if (count($task) > 2) {
////        list($class, $method, $args) = $task;
////        $class::$method($args);
////    } else {
////        list($class, $method) = $task;
////        $class::$method();
////    }
////
////}
//
//foreach ($queue as $task) {
//    if (count($task) > 2) {
//        list($class, $method, $args) = $task;
//        $class::$method($args);
//    } else {
//        list($class, $method) = $task;
//        $class::$method();
//    }
//}

echo $queue->count()."___\r\n";



register_shutdown_function('systemError');