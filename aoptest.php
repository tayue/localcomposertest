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
use App\Aop\ProxyFactoryDemo;
use App\Dao\OrderDao;

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
$className = OrderDao::class;
$ans = AnnotationRegister::getAnnotations();
$aspects=AnnotationRegister::getAspectAnnotations();
print_r($ans);
print_r($aspects);
initAspectAopAnnotationClass();


function initAspectAopAnnotationClass(){
    $aspectAns = AnnotationRegister::getAspectAnnotations();
    foreach ($aspectAns as $aspectKey => $eachAspectAn) {
        @list($className, $methodName) = explode("::", $aspectKey);
        if(AnnotationRegister::checkIsHasAspectAnnotation($className, $methodName)){
            echo "{$className} has aspect annotation ---------------\r\n";
            (new ProxyFactory())->loadProxy($className);
        }
    }
}


$className = OrderDao::class;
$methodName = "createUser";
if (AnnotationRegister::checkIsHasAspectAnnotation($className, $methodName)) {
    echo "{$className} has aspect annotation ---------------\r\n";
//    $pf = new ProxyFactoryDemo();
//    $code = $pf->loadProxy($className);
//
    $proxyClassName = $className . "Aop";
//
    $dao = DiPool::getInstance()->getSingleton($proxyClassName);
//
//    $ss = $diPool->getSingletons();
    $orderData = array(1, 2, 3);
    echo $res = $dao->$methodName($orderData);

}


