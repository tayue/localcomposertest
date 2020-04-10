<?php
require_once "../vendor/autoload.php";

use GuzzleHttp\Promise\Promise;

$promise = new Promise();



$promise
    ->then(function ($value) {
        echo $value.nl2br("\r\n");
         // Return a value and don't break the chain
        sleep(2);
        $value+=10;
        echo $value.nl2br("\r\n");
        return $value;
    })
    // This then is executed after the first then and receives the value
    // returned from the first then.
    ->then(function ($value) {
        echo $value." ".date("Y-m-d H:i:s");
    });

// Resolving the promise triggers the $onFulfilled callbacks and outputs
// "Hello, reader."
$promise->resolve(1);

die("----------------");

$nextPromise = new Promise();

$promise2 = new Promise();

$promise2
    ->then(function ($value) use ($nextPromise) {
        echo $value.nl2br("\r\n");
        // Return a value and don't break the chain
        return $nextPromise;
    })
    // This then is executed after the first then and receives the value
    // returned from the first then.
    ->then(function ($value) {
        echo $value." ".date("Y-m-d H:i:s");
    });

// Triggers the first callback and outputs "A"
$promise2->resolve('A');
// Triggers the second callback and outputs "B"
$nextPromise->resolve('B');



$promise = new Promise();
$promise->then(null, function ($reason) {
    echo $reason;
});

$promise->reject('Error!');
// Outputs "Error!"


//同步等待

$promise = new Promise(function () use (&$promise) {
    $promise->resolve('foo');
});

// Calling wait will return the value of the promise.
echo $promise->wait(); // outputs "foo"



$promise = new Promise(function () use (&$promise) {
    throw new \Exception('foo');
});

$promise->wait(); // throws the exception.