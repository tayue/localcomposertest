<?php

use Framework\SwServer\Router\Router;

if(Router::getFactory()){
    Router::addGroup('/home', function () {
        Router::get('/demo/index', 'App\Modules\Home\Controller\DemoController@indexAction');
    });
    Router::addServer('grpc', function () {
        Router::addGroup('/grpc.hi', function () {
            Router::post('/sayHello', 'App\Controller\HiController@sayHello');
            Router::post('/hello', 'App\Controller\GrpcController@hello');
            Router::get('/hello1', 'App\Controller\HiController@sayHello');
        });
    });
}


