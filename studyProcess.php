<?php

//定义进程数量
define('FORK_NUMS', 5);

//用于保存进程pid
$pids = array();

//我们创建5个子进程
for ($i = 0; $i < FORK_NUMS; ++$i) {
    $pids[$i] = pcntl_fork();
    if ($pids[$i] == -1) {
        die('fork error');
    } else if ($pids[$i]) {
        //这里是父进程空间，也就是主进程
        //我们的for循环第一次进入到这里时，pcntl_wait会挂起当前主进程，等待第一个子进程执行完毕退出
        //注意for循环的代码是在主进程的，挂起主进程，相当于当前的for循环也阻塞在这里了
        //第一个子进程退出后，然后再创建第二个子进程，到这里后又挂起，等待第二个子进程退出，继续创建第三个，等等。。
        pcntl_wait($status);
    } else {
        //这里是子进程空间
        echo "父进程ID: ", posix_getppid(), " 进程ID : ", posix_getpid(), " {$i} \r\n";
        //我们让子进程等待3秒，再退出
        sleep(3);
        exit;
    }
}