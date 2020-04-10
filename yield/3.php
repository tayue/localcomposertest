<?php
//function thunkify1($func){
//    return function () use ($func) {
//        $args = func_get_args();
//        return function ($callback) use ($args, $func) {
//            array_push($args, $callback);
//            return $func(...$args);
//        };
//    };
//};

//$printStr = function($p1, $p2, $callback) {
//    $callback($p1, $p2);
//};
//
//$printStrThunkify = thunkify($printStr);
//
//var_dump($printStrThunkify);
//
//$printStrThunkify(...["foo", "bar"])(function (...$p) {
//    var_dump($p);
//});


# output
//array(2) {
//    [0]=>
//  string(3) "foo"
//    [1]=>
//  string(3) "bar"
//}



function thunkify($func){
    return function () use ($func) {
        $args = func_get_args();
        return function ($callback) use ($args, $func) {
            // 原本的获取参数，回调会多次执行
            // array_push($args, $callback);
            // 增加回调只能执行一次
            $callbackCalled = false;
            array_push($args, function (...$params) use ($callback, &$callbackCalled) {
                if ($callbackCalled) return ;
                $callbackCalled = true;
                $callback(...$params);
            });
            return $func(...$args);
        };
    };
};

//$printStr = function($p1, $p2, $callback) {
//    $callback($p1, $p2);
//    $callback($p1, $p2); //我们增加一次回调
//};
//
//$printStrThunkify = thunkify($printStr);
//
//$printStrThunkify(...["foo", "bar"])(function (...$p) {
//    var_dump($p);
//});

# output
//array(2) {
//    [0]=>
//  string(3) "foo"
//    [1]=>
//  string(3) "bar"
//}




$printStr1 = function($p1, $callback) {
    //sleep(2);
    $callback($p1);
};
$printStr2 = function($p1, $callback) {
    //sleep(5);
    $callback($p1);
};

$printStrThunkify1 = thunkify($printStr1);
$printStrThunkify2 = thunkify($printStr2);

function gen()
{
    global $printStrThunkify1, $printStrThunkify2;

    $r1 = yield $printStrThunkify1("1");
    var_dump($r1);
    $r2 = yield $printStrThunkify2("2");
    var_dump($r2);
}

function autoCaller(\Generator $gen)
{
    // 注意这里的$next use 引入作用域必须带上&, 否则无法识别
    $next = function ($p1) use ($gen, &$next) {

        if (is_null($p1)) { //此处获取第一次yeild的回调
            $result = $gen->current();
        } else {
            // send后返回的是下一次的yield值
            $result = $gen->send($p1);
        }

        // 是否生成器迭代完成
        // 迭代器生成完成，不再迭代执行(自动执行器返回停止)
        if (!$gen->valid()) {
            return ;
        }

        $result($next);
    };

    $next(null);
}

$gen1 = gen();
//$gen2 = gen();

autoCaller($gen1);
//autoCaller($gen2);

# output
//string(1) "1"
//string(1) "2"
//
//# 如果我们打开上面的两个sleep()注释
//# output
//
//# 等待2秒
//string(1) "1"
//# 等待5秒
//string(1) "2"

# 因为这里我们的thunk里执行的实际函数是同步的代码，所以整体是阻塞的后续代码执行的