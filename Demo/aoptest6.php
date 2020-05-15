<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require_once '../vendor/autoload.php';

@define(RUNTIME_PATH, dirname(__DIR__) . "/runtime");

include_once './App/Config/defines.php';
$config = include_once './App/Config/config.php';
$serverConfig = include_once './App/Config/server.php';
$config = array_merge($config, $serverConfig);

use Framework\SwServer\Aop\ProxyFactory;
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
use App\Service\UserService;
use App\Aop\ProxyVisitorDemo;

//$servicename='userService';
////$diPool->registerService($servicename,UserService::class);
//$serviceObj=$diPool->get($servicename);
//print_r($serviceObj);
//print_r($diPool);
AnnotationRegister::getInstance()->load();
$diPool=DiPool::getInstance();
$res=$diPool->getSingletons();
print_r($res);
$className = Test::class;
$ans = AnnotationRegister::getAnnotations();
$aspectAns = AnnotationRegister::getAspectAnnotations();

if(AnnotationRegister::checkIsHasAspectAnnotation($className)){
    echo "{$className} has aspect annotation ---------------\r\n";
    $pf = new ProxyVisitorDemo();
    $pf->loadProxy($className);
    $proxyClassName = $className . "_" . md5($className);
    echo $proxyClassName . "\r\n";
    $proxyClass = new $proxyClassName();
    print_r($proxyClass);
    echo $res = $proxyClass->jisuan();
}else{
    echo "{$className} not has aspect annotation ---------------\r\n";
}






$diPool=DiPool::getInstance();
$servicename='userService';
//$diPool->registerService($servicename,UserService::class);
$serviceObj=$diPool->get($servicename);
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

