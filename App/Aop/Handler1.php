<?php


namespace App\Aop;


use Framework\SwServer\Aop\ProceedingJoinPoint;

class Handler1
{
    function handle(ProceedingJoinPoint $proceedingJoinPoint)
    {
        echo "#########start[" . Date("Y#m#d H:i:s") . "]###########\r\n";

        // 切面切入后，执行对应的方法会由此来负责
        // $proceedingJoinPoint 为连接点，通过该类的 process() 方法调用原方法并获得结果
        // 在调用前进行某些处理
        echo "#########处理逻辑###########\r\n";
        $result = $proceedingJoinPoint->process();
        $result=$result."&&&&";
        echo "return:".$result;
        echo "#########end[" . Date("Y#m#d H:i:s") . "]###########\r\n";
        // 在调用后进行某些处理
        return $result;
    }
}