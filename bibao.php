<?php

//php 闭包用法
$add = (function () {
    $counter = 0;
    $arr=range(1,10000);
    return function () use(&$counter,$arr) {return $counter++;};
    })();

var_dump($add);

echo $add()."\r\n";

echo $add()."\r\n";

echo $add()."\r\n";

echo $add()."\r\n";

echo $add()."\r\n";