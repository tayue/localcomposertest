<?php
declare(ticks=1); #写在第一行的 反正我是这样的

$xxoo = getmypid();
echo "我是正宗的父ID：" . getmypid() . "\r\n";

for ($x = 0; $x < count($taskarr); $x++) {

    pcntl_signal(SIGCHLD, 'sig_func');#一个信号接收，用于回收进程的

    $pid = pcntl_fork();
    if (-1 == $pid) {
        die('Fork failed');
    } else if ($pid > 0) {
//       echo $x."第子进程的ID".$pid."\r\n"; 
//       echo "正宗的父ID是：".$xxoo."\r\n"; 
//       echo $pid."的父进程ID".getmypid()."\r\n"; 
        if ($xxoo != getmypid()) {
//         echo getmypid()."是个假的父ID\r\n";
            posix_kill($pid, 9);
        }
        pcntl_waitpid($pid, $status); #等待进程结束，如果是僵尸进程直接回收 大概吧 我强行翻译成这样的

    } else {
#子进程需要执行的逻辑代码

        echo $x . "----" . getmypid() . "\r\n";
    }

}

function sig_func()
{
    $status = -1;
     pcntl_wait($status);
}


