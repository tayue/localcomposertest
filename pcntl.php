<?php
$i=0;
while($i!=5){
    $pid = pcntl_fork();
    echo $pid."---------hahah".$i++.PHP_EOL;
    if ($pid == 0) {
        echo "子进程".PHP_EOL;
        return;
    }
}