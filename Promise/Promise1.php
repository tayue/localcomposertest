<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
require_once "./classes/Promise1.php";
$promise = new Promise1(function($resolve, $reject) {
    $flag=$resolve("faild");
    if(!$flag){
        $reject("reject");
    }
});

var_dump($promise->getState());
var_dump($promise->getValue());
var_dump($promise->getReason());