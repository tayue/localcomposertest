<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
require_once "./classes/Promise2.php";

$promise = new Promise2(function($resolve, $reject) {
    $flag=$resolve("ok");
    if(!$flag){
        $reject("reject");
    }
});

$promise->then(function ($value) {
    var_dump($value);
}, function ($reason) {
    var_dump($reason);
})->then(function ($value) {
    var_dump($value);
}, function ($reason) {
    var_dump($reason);
});