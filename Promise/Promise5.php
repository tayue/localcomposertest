<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
require_once "./classes/Promise5.php";

$promise1 = new Promise5(function($resolve, $reject) {
    $resolve("打印我");
});
print_r($promise1);
$promise2 = $promise1->then(function ($value) {
    var_dump($value);
    throw new \Exception("promise2 error");
    return "promise2";
}, function ($reason) {
    var_dump($reason->getMessage());
    return "promise3 error return";
});
print_r($promise2);
//我们可以简写then方法，只传入$onFulfilled方法，然后错误会自己冒泡方式到下一个catchError或then里处理。
//$promise3 = $promise2->then(function ($value) {
//    var_dump($value);
//    return new Promise5(function($resolve, $reject) {
//        $resolve("promise3");
//    });
//})->catchError(function ($reason) {
//    var_dump($reason->getMessage());
//    return "promise3 error return";
//});

$promise3 = $promise2->then(function ($value) {
    var_dump($value);
    return new Promise5(function($resolve, $reject) {
        $resolve("promise3");
    });
}, function ($reason) {
    var_dump($reason->getMessage());
    return "promise3 error return";
});
print_r($promise3);
$promise4 = $promise3->then(function ($value) {
    var_dump($value);
    return "promise4";
}, function ($reason) {
    echo $reason->getMessage();
});

var_dump($promise4);