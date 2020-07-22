<?php

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require_once '../vendor/autoload.php';
use Framework\Tool\Pipeline;
use Framework\SwServer\Http\PipelineHttpHandleAop;

$arr = array(
    array('min' => 1.5456, 'max' => 2.28548, 'volume' => 23.152),
    array('min' => 1.5457, 'max' => 2.28549, 'volume' => 23.152),
    array('min' => 1.5458, 'max' => 2.28550, 'volume' => 23.152),
    array('min' => 1.5459, 'max' => 2.28551, 'volume' => 23.152),
    array('min' => 1.5460, 'max' => 2.28552, 'volume' => 23.152),
);

$initial = array_shift($arr);

$t = array_reduce($arr, function($result, $item) {
    $result['min'] = min($result['min'], $item['min']);
    $result['max'] = max($result['max'], $item['max']);
    $result['volume'] += $item['volume'];

    return $result;
}, $initial);


/**
 * 前置管道1
 */
class Handler1
{
    function handle($poster,$callback)
    {

        $poster--;
        echo '处理器1' . "\n";

        return $callback($poster);
    }
}

/**
 * 前置管道2
 */
class Handler2
{
    function handle($poster,$callback)
    {
        echo '处理器2' . "\n";
        $poster--;
        return $callback($poster);
    }
}

/**
 * 后置管道3
 */
class Handler3
{
    function handle($poster,$callback)
    {
        $a=func_get_args();
        $poster--;
        echo '处理器3' . "\n";

        return $callback($poster);
    }
}

/**
 * 前置管道4
 */
class Handler4
{
    function handle($poster,$callback)
    {
        echo '处理器4' . "\n";
        $poster--;
        return $callback($poster);
    }
}

/**
 * 前置管道4
 */
class Handler5
{
    function handle($poster,$callback)
    {
        echo '处理器' . "\n";

        return $callback();
    }
}


    $pipes = [
        function ($poster, $callback) {
            echo $poster."\r\n";
            $poster += 1;
            echo $poster."\r\n";
            var_dump($callback($poster));
            return $callback($poster);
        },
        function ($poster, $callback) {
            $result = $callback($poster);

            return $result - 1;
        },
        function ($poster, $callback) {
            $poster += 2;

            return $callback($poster);
        }
    ];

class HttpHandleMiddle{
    public $handler;
    public function __construct($handler)
    {
        $this->handler=$handler;
    }

    function handle($request,$callback)
    {
        echo '处理器' . "\n";
        $this->handler->handle($request);
        return $callback($request);
    }
}

class Handler{
    public function handle($request){
        $request+=1;
        return $request;
    }
}

try{
    //$pipes1=[Handler1::class.":handle",Handler2::class.":handle",Handler3::class.":handle",Handler4::class.":handle"];
    //$pipes=[new Handler1(),new Handler2(),new Handler3(),new Handler4()];
    $middles=[new HttpHandleMiddle(new Handler())];
    $request=1;
//  $res= (new Pipeline())->send(function($a){
//      return $a;
//  })->through($pipes1)->then(function ($post) {
//        return $post;
//    }); // 执行输出为 2
    $res= (new PipelineHttpHandleAop())->send($request)->through($pipes1)->then(function ($request) {
         return $request;
    });
    var_dump($res);
}catch (Throwable $e){
     //print_r($e);
}

$param=3;
$func=function($a=1) use($param){
    var_dump($a,$param);
};

$func();


//return (new Pipeline($this->app)) # 传入 app 实例(单例)
//->send($request) # 传入 request，赋值给 Pipeline 的 passable 属性
//->through($this->app->shouldSkipMiddleware() ? [] : $this->middleware) # 传入中间件，这个判断是为了测试时候禁用中间件而写的，把 对应的中间件类传入，赋值给 Pipeline 的 pipes
//->then($this->dispatchToRouter());

//use League\Pipeline\Pipeline;
//
//$num=$_REQUEST['num'];
//
//// 创建两个闭包函数
//
//$pipe1 = function ($payload) {
//
//    return $payload + 1;
//
//};
//
//
//
//$pipe2 = function ($payload) {
//
//    return $payload * 3;
//
//};
//
//
//// 正常使用
//
//$pipeline1 = (new Pipeline)
//
//    ->pipe($pipe1)
//
//    ->pipe($pipe2);
//
//
//
//$callback1 = $pipeline1->process($num);
//
//
//
//echo ("<h1>正常使用</h1>");
//
//echo ("<p>结果：$callback1</p>");

//$route->map(
//
//    'GET',
//
//    '/demo',
//
//    function (ServerRequestInterface $request, ResponseInterface $response
//
//    ) use ($service, $pipe1, $pipe2) {
//
//        $params = $request->getQueryParams();
//
//
//
//        // 正常使用
//
//        $pipeline1 = (new Pipeline)
//
//            ->pipe($pipe1)
//
//            ->pipe($pipe2);
//
//
//
//        $callback1 = $pipeline1->process($params['data']);
//
//
//
//        $response->getBody()->write("<h1>正常使用</h1>");
//
//        $response->getBody()->write("<p>结果：$callback1</p>");
//
//
//
//        // 使用魔术方法
//
//        $pipeline2 = (new Pipeline())
//
//            ->pipe($pipe1)
//
//            ->pipe($pipe2);
//
//
//
//        $callback2 = $pipeline2($params['data']);
//
//
//
//        $response->getBody()->write("<h1>使用魔术方法</h1>");
//
//        $response->getBody()->write("<p>结果：$callback2</p>");
//
//
//
//        // 使用 Builder
//
//        $builder = new PipelineBuilder();
//
//        $pipeline3 = $builder
//
//            ->add($pipe1)
//
//            ->add($pipe2)
//
//            ->build();
//
//
//
//        $callback3 = $pipeline3($params['data']);
//
//
//
//        $response->getBody()->write("<h1>使用 Builder</h1>");
//
//        $response->getBody()->write("<p>结果：$callback3</p>");
//
//        return $response;
//
//    }
//
//);