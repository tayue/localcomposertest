<?php

declare(ticks=1);

// 开始时间
$time_start = time();

// 检查是否已经超时
function check_timeout()
{
    echo "---\r\n";
    // 开始时间
    global $time_start;
    // 5秒超时
    $timeout = 5;
    if (time() - $time_start > $timeout) {
        exit("overtime{$timeout}Seconds\n");
    }
}

// Zend引擎每执行一次低级语句就执行一下check_timeout
register_tick_function('check_timeout');

// 模拟一段耗时的业务逻辑
while (1) {
    sleep(1);

}
