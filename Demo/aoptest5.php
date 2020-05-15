<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require_once '../vendor/autoload.php';

use Framework\SwServer\Annotation\AnnotationRegister;
use Framework\SwServer\Annotation\ComposerHelper;
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
ServerManager::$config=$config;


AnnotationRegister::getInstance([
    'restrictedPsr4Prefixes' => ['Psr\\', 'PHPUnit\\', 'Symfony\\','Monolog\\'],
    'onlyScanNamespaces' => ['App\\'],
    'handlerCallback' => function ($type, ...$params) {
        if (method_exists(AnnotationRegister::class, $type)) {
            call_user_func_array([AnnotationRegister::class, $type], $params);
        }
    }
])->load();
$diPool=DiPool::getInstance();
$file= realpath("../App/Controller/AnnotationDemo.php");
$code = file_get_contents($file);

$parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
$ast = $parser->parse($code);

$traverser = new NodeTraverser();
$className = 'App\\Controller\\AnnotationDemo';
$proxyId='Aop';
$visitor = new ProxyVisitorDemo($className, $proxyId);
$traverser->addVisitor($visitor);
$proxyAst = $traverser->traverse($ast);

$className = OrderDao::class;
$ans = AnnotationRegister::getAnnotations();
$aspectAns = AnnotationRegister::getAspectAnnotations();
print_r($aspectAns);
$methodName="createOrder";
if(AnnotationRegister::checkIsHasAspectAnnotation($className,$methodName)){
    echo "{$className} has aspect annotation ---------------\r\n";
    $pf = new ProxyFactoryDemo();
    $pf->loadProxy($className);
    //$proxyClassName = $className . "_" . md5($className);
    //echo $proxyClassName . "\r\n";
    //$proxyClass = new $proxyClassName();
    //print_r($proxyClass);
    $dao=$diPool->getSingleton($className);
    print_r($dao);
    echo $res = $dao->createOrder();
}else{
    echo "{$className} not has aspect annotation ---------------\r\n";
}



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
//print_r($bean);
//
//echo $bean->test()."\r\n\r\n\r\n\r\n";


//echo $proxyCode;

