<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
require_once "./classes/Promise3.php";

$promise = new Promise3(function($resolve, $reject) {
    $resolve("打印我");
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