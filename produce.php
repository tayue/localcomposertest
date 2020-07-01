<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require_once './vendor/autoload.php';
@define(RUNTIME_PATH, __DIR__ . "/runtime");

use App\Service\RabbitMqService;


go(function () {
    $exchange = 'test_exchange_confirm';
    $queue = 'test_queue_confirm';
    $route_key = 'test_confirm';
    $message = "hello world!!!";
    $arr = [];
    for ($i = 1; $i <= 100; $i++) {
        $arr[] = $message . $i;
    }
    $res = RabbitMqService::produceMessage($arr, $exchange, $queue, $route_key);
    if ($res) {
        echo "Send Message:" . $message . $i . " Success\r\n";
    }

});