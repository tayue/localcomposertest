<?php



require './vendor/autoload.php';//引入predis相关包
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

use FastRoute\RouteParser\Std;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\Dispatcher\GroupCountBased as DispatcherGroupCountBased;
use FastRoute\RouteCollector;



/** @var RouteCollector $routeCollector */
$routeCollector = new RouteCollector(
    new Std, new GroupCountBased
);
$routeCollector->addRoute('GET', '/users', 'App\Controller\BlogController@show');
// {id} 必须是一个数字 (\d+)
$routeCollector->addRoute(array('GET','POST'), '/user/{id:\d+}', 'App\Controller\BlogController@show');
//  /{title} 后缀是可选的
$routeCollector->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'App\Controller\BlogController@article');

$dispatcher=new DispatcherGroupCountBased($routeCollector->getData());




$class = new \ReflectionClass('App\Controller\BlogController');

$methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);


print_r($_SERVER);
// 获取请求的方法和 URI
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = str_ireplace("/fastroute.php", "", $_SERVER['REQUEST_URI']);

// 去除查询字符串( ? 后面的内容) 和 解码 URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

print_r($routeInfo);

try {

    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            // ... 404 Not Found 没找到对应的方法
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $allowedMethods = $routeInfo[1];
            // ... 405 Method Not Allowed  方法不允许
            break;
        case FastRoute\Dispatcher::FOUND: // 找到对应的方法
            $handler = $routeInfo[1]; // 获得处理函数
            $vars = $routeInfo[2]; // 获取请求参数
            list($controller, $action) = explode("@", $handler); //路由实际应用
            if (class_exists($controller)) {
                if (method_exists($controller, $action)) {
                    $controllers = new $controller();
                    $vars = array_values($vars);
                    call_user_func([$controllers, $action], ...$vars);
                } else {
                    new Exception("class no exists action!!!!");
                }
            } else {
                new Exception("class no exists!!!!");
            }

            // ... call $handler with $vars // 调用处理函数
            break;
    }

} catch (Exception $e) {
   echo $e->getMessage();
}