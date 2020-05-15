<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require_once './vendor/autoload.php';
@define(RUNTIME_PATH, __DIR__ . "/runtime");

use Framework\SwServer\Annotation\AnnotationRegister;
use Framework\SwServer\Aop\ProxyFactory;
use Framework\SwServer\Pool\DiPool;
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

include_once './App/Config/defines.php';
$config = include_once './App/Config/config.php';
$serverConfig = include_once './App/Config/server.php';
$config = array_merge($config, $serverConfig);
ServerManager::$config = $config;
AnnotationRegister::getInstance([
    'onlyScanNamespaces' => ['App\\'],
    'handlerCallback' => function ($type, ...$params) {
        if (method_exists(AnnotationRegister::class, $type)) {
            call_user_func_array([AnnotationRegister::class, $type], $params);
        }
    }
])->load();
$diPool = DiPool::getInstance();

$ans = AnnotationRegister::getAnnotations();

initAspectAopAnnotationClass();





$dis=DiPool::getInstance()->getSingletons();
print_r($dis);

////通过类名获取
//$workClass_by_classname = new ReflectionClass('Worker');
//
//
////通过类的实例对象获取
//$w = new Worker("小明",20,20);
//$workerClass_by_classinstance = new ReflectionObject($w);


$className = OrderDao::class;
$proxyClassName = "OrderDao_" . md5($className);
$methodName = "createUser";






