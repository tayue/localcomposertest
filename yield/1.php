<?php
function gen($max)
{
    for ($i=0; $i<$max; $i++) {
        // 此处的(yield $i)在php7以后版本可省略
        $res = (yield $i);
        var_dump($res);
    }

    return $max;
}

$gen = gen(10);

// 可不调用，隐式调用
// 如果迭代开始后不能再rewind（即使用了next或send后）
$gen->rewind();

// 打印获取到当前生成器的值
var_dump("1::" . $gen->current()); //output: string(4) "1::0"

// 下面2句代码执行，将返回错误
// $gen->next();
// $gen->rewind();

//继续执行，知道遇到下一个yield
$gen->next();
var_dump("2::" . $gen->current()); //output: string(4) "2::1"
$gen->next();
var_dump("3::" . $gen->current()); //output: string(4) "3::2"

// send传null值等同于调用next（本方法尝试来自python的迭代器，成功）
$gen->send(null);
var_dump("4::" . $gen->current()); //output: string(4) "4::3"

// send传值会也会继续执行
$gen->send(100);
var_dump("5::" . $gen->current()); //output: string(4) "5::4"


//如果已经迭代完成，获取返回值
// php7 支持
if (version_compare(PHP_VERSION, '7.0.0') >= 0 && !$gen->valid()) {
    var_dump($gen->getReturn());
}

# output:
//string(4) "1::0"
//NULL
//string(4) "2::1"
//NULL
//string(4) "3::2"
//NULL
//string(4) "4::3"
//int(100)
//string(4) "5::4"

# 我们先不去理会gen里var_dump输出的NULL或int(100)
# 我们先去理解每次next后current可以获取到当前yield的值即可