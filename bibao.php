<?php

use Swoole\Coroutine;

function call($cb, array $args = [])
{
    $ret = null;
    if (is_object($cb) || (\is_string($cb) && \function_exists($cb))) {
        echo '111';
        $ret = $cb(...$args);
    } elseif (\is_array($cb)) {
        list($obj, $mhd) = $cb;
        $ret = \is_object($obj) ? $obj->$mhd(...$args) : $obj::$mhd(...$args);
    } else {
        if (SWOOLE_VERSION >= '4.0') {
            $ret = call_user_func_array($cb, $args);
        } else {
            $ret = Coroutine::call_user_func_array($cb, $args);
        }
    }

    return $ret;
}

$a = function () {
    echo '333';
};

var_dump(is_object($a), $a instanceof Closure);
$a = call($a);
var_dump($a);

////php 闭包用法
//$add = (function () {
//    $counter = 0;
//    $arr=range(1,10000);
//    return function () use(&$counter,$arr) {return $counter++;};
//    })();
//
//var_dump($add);
//
//echo $add()."\r\n";
//
//echo $add()."\r\n";
//
//echo $add()."\r\n";
//
//echo $add()."\r\n";
//
//echo $add()."\r\n";