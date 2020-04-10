<?php
# 本代码手动调整了进程执行代码的顺序，当然本代码实现不用协程也可以，只是利用本流程说明协程作用
# 生成器给了我们函数中断，协程[生成器send]给了我们重新唤起生成器函数的能力
function printNumWithGen($max)
{
    for ($i=0; $i<$max; $i++ ) {
        $res = yield $i;
        echo $res;
    }
}

$gen1 = printNumWithGen(3);
$gen2 = printNumWithGen(3);

// 手动执行caller1 再 caller2
$gen1->send("调度者: caller1 打印:" . $gen1->current() . PHP_EOL);
$gen2->send("调度者: caller2 打印:" . $gen2->current() . PHP_EOL);

// 手动执行caller1 再 caller2
$gen1->send("调度者: caller1 打印:" . $gen1->current() . PHP_EOL);
$gen2->send("调度者: caller2 打印:" . $gen2->current() . PHP_EOL);

// 手动执行caller2 再 caller1
$gen2->send("调度者: caller2 打印:" . $gen2->current() . PHP_EOL);
$gen1->send("调度者: caller1 打印:" . $gen1->current() . PHP_EOL);

# output
//调度者: caller1 打印:0
//调度者: caller2 打印:0
//调度者: caller1 打印:1
//调度者: caller2 打印:1
//调度者: caller2 打印:2
//调度者: caller1 打印:2