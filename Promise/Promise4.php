<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
require_once "./classes/Promise4.php";

$promise1 = new Promise4(function($resolve, $reject) {
    $resolve("打印我");
});

$promise2 = $promise1->then(function ($value) {
    var_dump($value);
    return "promise2";
}, function ($reason) {
    var_dump($reason);
});
var_dump($promise2);
$promise3 = $promise2->then(function ($value) {
    var_dump($value);
    return new Promise4(function($resolve, $reject) {
        $resolve("promise3");
    });
}, function ($reason) {
    var_dump($reason);
});
var_dump($promise3);
$promise4 = $promise3->then(function ($value) {
    var_dump($value);
    return "promise4";
}, function ($reason) {
    var_dump($reason);
});

var_dump($promise4);