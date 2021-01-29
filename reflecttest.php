<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require_once './vendor/autoload.php';
@define(RUNTIME_PATH, __DIR__ . "/runtime");

use App\Service\UserService;
use Framework\SwServer\Annotation\AnnotationRegister;
use Framework\SwServer\Aop\ProxyFactory;
use Framework\SwServer\Pool\DiPool;
use Framework\SwServer\Router\DispatcherFactory;
use Framework\SwServer\Rpc\Router\Router;
use Framework\SwServer\Rpc\Router\RouteRegister;
use Framework\SwServer\ServerManager;
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use Framework\SwServer\Aop\ProxyVisitor;
use PhpParser\PrettyPrinter\Standard;
use PhpParser\NodeDumper;
use Framework\SwServer\Aop\Ast;
use App\Aop\ProxyVisitorDemo;
use Framework\SwServer\Aop\AopProxyFactory;
use App\Dao\OrderDao;
use App\Dao\OrderDaoAopDemo;

include_once './Config/defines.php';
$config = include_once './Config/config.php';
$serverConfig = include_once './Config/server.php';

//$orderDao = new App\Dao\OrderDao();
//
// echo get_class($orderDao);

$config = array_merge($config, $serverConfig);

ServerManager::$config = $config;
$di = DiPool::getInstance();
AnnotationRegister::getInstance([
    'onlyScanNamespaces' => ['App\\'],
    'handlerCallback' => function ($type, ...$params) {
        if (method_exists(AnnotationRegister::class, $type)) {
            call_user_func_array([AnnotationRegister::class, $type], $params);
        }
    }
])->load();
//$sis = $di->getSingletons();
//
//print_r(count($sis));
////$router = DiPool::getInstance()->getSingleton(Router::class);
////RouteRegister::registerRoutes($router);
//
////$routes = AnnotationRegister::getRouteAnnotations();
////print_r($routes);
////$diPool = DiPool::getInstance();
////
////$ans = AnnotationRegister::getAnnotations();
//
//print_r($di['orderDao']);
////print_r(count($sis));
//echo (count($di->getParams()))."--\r\n";
//
//unset($di['orderDao']);
////print_r(count($di->getResolvedEntries()));
//echo (count($di->getParams()))."##\r\n";

//print_r(count($sis));

//$routeAnno = AnnotationRegister::getRouteAnnotations();
//print_r($routeAnno);

//$request_uri = '/site/index/index/2';
//$df = DiPool::getInstance()->getSingleton(DispatcherFactory::class);
//$httpMethod = 'GET';
//$dispatcher = $df->getDispatcher();
//$uri = rawurldecode($request_uri);
//
////$coroutineId = ServerManager::getApp()->coroutine_id; //当前应用对象协程id
////        $_module && $_module = ucfirst($_module);
////        $_module && ServerManager::$app[$coroutineId]->current_module = $_module;
////        $_controller && $_controller = ucfirst($_controller);
////        $_controller && ServerManager::$app[$coroutineId]->current_controller = $_controller;
////        $_action && ServerManager::$app[$coroutineId]->current_action = $_action;
//$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
//print_r($routeInfo);
//print_r($df);
//$httpMethod = $request->server['request_method'];
//$dispatcher = $df->getDispatcher();
//$dd = $df->getRouter();
//print_r($dd);
//
//initAspectAopAnnotationClass();
//
//
//
//
//

//$dis=DiPool::getInstance()->getSingletons();
//print_r($dis);
//$userService = DiPool::getInstance()->getSingleton(UserService::class);
//print_r($userService->eat());
//
//////通过类名获取
////$workClass_by_classname = new ReflectionClass('Worker');
////
////
//////通过类的实例对象获取
////$w = new Worker("小明",20,20);
////$workerClass_by_classinstance = new ReflectionObject($w);
//
//
//$className = OrderDao::class;
//$proxyClassName = "OrderDao_" . md5($className);
//$methodName = "createUser";






