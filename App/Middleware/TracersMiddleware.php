<?php

namespace App\Middleware;

use Framework\SwServer\Pool\DiPool;
use Framework\SwServer\Tracer\TracerFactory;
use OpenTracing\Span;
use OpenTracing\Tracer;
use Framework\Traits\SpanStarter;
use Swoole\Http\Request;
use Framework\SwServer\Coroutine\CoroutineManager;
use App\Handler\TracerHandler as requestHandler;
use Framework\SwServer\Http\HttpJoinPoint;
use App\Annotation\Bean;

use const OpenTracing\Formats\TEXT_MAP;
use const OpenTracing\Tags\SPAN_KIND;
use const OpenTracing\Tags\SPAN_KIND_RPC_SERVER;

class TracersMiddleware
{
    /**
     * @Bean(name="App\Handler\TracerHandler")
     */
    public $handler = null;


    public $tracer;


    public function __construct()
    {
        $container = DiPool::getInstance();
        $TracerFactory = $container->getSingleton(TracerFactory::class);
        $this->tracer = $TracerFactory->getTracer();
    }


    public function process(HttpJoinPoint $httpJoinPoint)
    {
        print_r($this->handler);
        $request = CoroutineManager::get('tracer.request');
        $response = $this->handler->handle($request);
        $response && CoroutineManager::set('tracer.response', $response);
        $response = $httpJoinPoint->process();
        return $response;
    }



}