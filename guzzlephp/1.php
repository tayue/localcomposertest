<?php

require_once '../vendor/autoload.php';

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

//$client = new Client();
//
//$requests = function ($total) {
//    $uri = 'http://127.0.0.1:8126/guzzle-server/perf';
//    for ($i = 0; $i < $total; $i++) {
//        yield new Request('GET', $uri);
//    }
//};
//
//$pool = new Pool($client, $requests(100), [
//    'concurrency' => 5,
//    'fulfilled' => function ($response, $index) {
//        // this is delivered each successful response
//    },
//    'rejected' => function ($reason, $index) {
//        // this is delivered each failed request
//    },
//]);
//
//// Initiate the transfers and create a promise
//$promise = $pool->promise();
//
//// Force the pool of requests to complete.
//$promise->wait();



$client = new Client(['base_uri' => 'http://httpbin.org/']);

// Initiate each request but do not block
$promises = [
    'image' => $client->getAsync('/image'),
    'png'   => $client->getAsync('/image/png'),
    'jpeg'  => $client->getAsync('/image/jpeg'),
    'webp'  => $client->getAsync('/image/webp')
];

// Wait on all of the requests to complete.
$results = Promise\unwrap($promises);

// You can access each result using the key provided to the unwrap
// function.
echo $results['image']->getHeader('Content-Length');
echo $results['png']->getHeader('Content-Length');