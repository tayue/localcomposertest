<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require_once '../vendor/autoload.php';

use Framework\SwServer\Aop\PipelineAop;
use Framework\Tool\Pipeline;
use Framework\SwServer\Aop\ProceedingJoinPoint;

use PhpParser\Node\Stmt\Class_;

class Handler1
{
    function handle(ProceedingJoinPoint $proceedingJoinPoint)
    {
        echo "---------start[" . Date("Y-m-d H:i:s") . "]-----------\r\n";

        // 切面切入后，执行对应的方法会由此来负责
        // $proceedingJoinPoint 为连接点，通过该类的 process() 方法调用原方法并获得结果
        // 在调用前进行某些处理
        echo "---------处理逻辑-----------\r\n";
        $result = $proceedingJoinPoint->process();
        var_dump($result);
        echo "---------end[" . Date("Y-m-d H:i:s") . "]-----------\r\n";
        // 在调用后进行某些处理
        return $result;
    }
}

class Handler2
{
    function handle($passable, $callback)
    {
        $passable->num++;
        echo "ProceedingJoinPoint:" . $passable->num . "\r\n";
        echo '处理器2' . "\n";

        return $callback($passable);
    }
}

/**
 * 后置管道3
 */
class Handler3
{
    function handle($passable, $callback)
    {

        $passable->num++;
        echo "ProceedingJoinPoint:" . $passable->num . "\r\n";
        echo '后置管道3' . "\n";

        return $callback($passable);
    }
}

/**
 * 后置管道4
 */
class Handler4
{
    function handle($passable, $callback)
    {
        $passable->num++;
        echo "ProceedingJoinPoint:" . $passable->num . "\r\n";
        echo '后置管道4' . "\n";
        return $callback($passable);
    }
}


try {
    $pipes1 = array(Handler1::class);
    //$pipes=[new Handler1(),new Handler2(),new Handler3(),new Handler4()];
    $closure = function ($a, $b) {
        return $a + $b;
    };

    $proceedingJoinPoint = new ProceedingJoinPoint($closure, "test", 'test', array(1, 2));


    $res = (new PipelineAop())->send($proceedingJoinPoint)->through($pipes1)->then(function ($proceedingJoinPoint) {
        return $proceedingJoinPoint->processOriginalMethod();
    });
    var_dump($res);
} catch (Throwable $e) {
    print_r($e);
}