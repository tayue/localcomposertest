<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require_once './vendor/autoload.php';

@define(RUNTIME_PATH, __DIR__ . "/runtime");

include_once './App/Config/defines.php';
$config = include_once './App/Config/config.php';
$serverConfig = include_once './App/Config/server.php';
$config = array_merge($config, $serverConfig);
use App\Rpc\Service\RpcUserService;
use Framework\SwServer\Aop\ProxyFactory;
use Framework\SwServer\ServerManager;
use  App\Rpc\Contract\UserInterface;
use Framework\SwServer\Annotation\AnnotationRegister;
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use Framework\SwServer\Aop\ProxyVisitor;
use PhpParser\PrettyPrinter\Standard;
use PhpParser\NodeDumper;
use Framework\SwServer\Aop\Ast;
use App\Controller\AnnotationDemo;
use App\Controller\BlogController;
use Framework\SwServer\Pool\DiPool;

use App\Dao\OrderDao;
use Framework\Di\ServerContainer;
use Framework\Core\error\CustomerError;
use Framework\Core\log\Log;
use Framework\SwServer\Aop\AopProxyFactory;

use Framework\SwServer\Coroutine\CoroutineManager;
use Framework\Core\Db;
use Framework\SwServer\Base\BaseObject;
use Framework\SwServer\Guzzle\ClientFactory;

use Framework\SwServer\Tracer\HttpClientFactory;
use Framework\SwServer\Tracer\TracerFactory;
use Framework\Traits\ServerTrait;
use Framework\SwServer\Protocol\TcpServer;
use Framework\SwServer\Router\DispatcherFactory;
use Framework\SwServer\Rpc\Router\RouteRegister;
//$servicename='userService';
////$diPool->registerService($servicename,UserService::class);
//$serviceObj=$diPool->get($servicename);
//print_r($serviceObj);
//print_r($diPool);

function initTracker()
{
    $container = DiPool::getInstance();
    $container->setSingletonByObject(ClientFactory::class, new ClientFactory($container));
    $container->setSingletonByObject(HttpClientFactory::class, new HttpClientFactory($container->getSingleton(ClientFactory::class)));
    $container->setSingletonByObject(TracerFactory::class, new TracerFactory($container->getSingleton(HttpClientFactory::class)));
}

initTracker();

ServerManager::$config = $config;




//Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');
//
//Router::addRoute(['GET', 'POST', 'HEAD'], '/user/index', 'App\Controller\UserController@index');

    $diPool = DiPool::getInstance($config);
    $services = $diPool->getServices();
use Framework\SwServer\Rpc\Router\Router;
try {
    AnnotationRegister::getInstance([
        'restrictedPsr4Prefixes' => ['Psr\\', 'PHPUnit\\', 'Symfony\\', 'Monolog\\'],
        'onlyScanNamespaces' => ['App\\'],
        'handlerCallback' => function ($type, ...$params) {
            if (method_exists(AnnotationRegister::class, $type)) {
                call_user_func_array([AnnotationRegister::class, $type], $params);
            }
        }
    ])->load();

    $res=DiPool::getInstance()->getSingletons();
    print_r($res);
    RouteRegister::getServices();
    $router=DiPool::getInstance()->getSingleton(Router::class);

    RouteRegister::registerRoutes($router);

    $is=RouteRegister::hasRouteByClassName(RpcUserService::class);
    if($is){
        $isMatch=$router->match("2",UserInterface::class);
        if($isMatch){
            print_r($isMatch);
            echo 'match----';
        }


    }
    die("ff");


    } catch (\Exception $e) {
    print_r($e->getTrace());
        echo $e->getMessage();

    }





//$diPool=DiPool::getInstance();
//$res=$diPool->getSingletons();
//print_r($res);
//
//$dao=$diPool->getSingleton(OrderDao::class);
//print_r($dao->createOrder());
//
//$className = OrderDao::class;
//$ans = AnnotationRegister::getAnnotations();
//$aspectAns = AnnotationRegister::getAspectAnnotations();
//print_r($aspectAns);
//$methodName="createOrder";
//if(AnnotationRegister::checkIsHasAspectAnnotation($className,$methodName)){
//    echo "{$className} has aspect annotation ---------------\r\n";
//    $pf = new ProxyFactory();
//    $pf->loadProxy($className);
//    //$proxyClassName = $className . "_" . md5($className);
//    //echo $proxyClassName . "\r\n";
//    //$proxyClass = new $proxyClassName();
//    //print_r($proxyClass);
//    $dao=$diPool->getSingleton($className);
//    print_r($dao);
//    echo $res = $dao->createOrder();
//}else{
//    echo "{$className} not has aspect annotation ---------------\r\n";
//}


$diPool = DiPool::getInstance();
$servicename = 'userService';
//$diPool->registerService($servicename,UserService::class);
$serviceObj = $diPool->get($servicename);
var_dump($serviceObj);

//if (!$proxyAst) {
//    throw new \Exception(sprintf('Class %s AST optimize failure', $className));
//}
//$printer = new Standard();
//$proxyCode = $printer->prettyPrint($proxyAst);
//var_dump($proxyCode);
//
//eval($proxyCode);
//
//$class = $visitor->getClassName();
//
//$bean = new $class();
//
//echo $bean->show()."\r\n\r\n\r\n\r\n";
//
//echo $bean->jisuan()."\r\n";

//echo $proxyCode;

