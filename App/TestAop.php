<?php

namespace App;

use App\Annotation\BeforeAspect;
use App\Annotation\AfterAspect;
use App\Annotation\ClassAspect;
use App\Aop\AbstractTest;
class TestAop extends \App\Test
{
    use \Framework\SwServer\Aop\AopTrait;
    private $property = "I am a private property!";
    public function show()
    {
        return $this->__proxyCall(function () {
            echo "exclute Method show()==>" . date("Y-m-d H:i:S") . "\r\n";
            return 'hello world';
        }, 'show', func_get_args(), 'App\\Test');
    }
    public function jisuan()
    {
        return $this->__proxyCall(function () {
            echo "exclute Method jisuan()==>" . date("Y-m-d H:i:S") . "\r\n";
            return 0;
        }, 'jisuan', func_get_args(), 'App\\Test');
    }
}