<?php

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require_once '../vendor/autoload.php';



use App\Annotation\Route;
use App\Controller\UserController;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\FileCacheReader;












//echo '<pre>';

// 注释使用自己的自动加载机制来确定给定的注释是否具有可以自动加载的对应PHP类
// 配置注释自动加载(2.0版本中已剔除)
AnnotationRegistry::registerLoader('class_exists'); //回调需返回true
//AnnotationRegistry::registerFile(__DIR__ . '/Util/Annotation/Route.php'); //注册文件
AnnotationRegistry::registerAutoloadNamespace('App\Annotation'); //注册命名空间
//AnnotationRegistry::registerAutoloadNamespaces(['Util\\Annotation' => null]); //注册多个命名空间

// 系统默认 var、author 标记不会识别
$whitelist = [
    "after", "afterClass", "backupGlobals", "backupStaticAttributes", "before", "beforeClass", "codeCoverageIgnore*",
    "covers", "coversDefaultClass", "coversNothing", "dataProvider", "depends", "doesNotPerformAssertions",
    "expectedException", "expectedExceptionCode", "expectedExceptionMessage", "expectedExceptionMessageRegExp", "group",
    "large", "medium", "preserveGlobalState", "requires", "runTestsInSeparateProcesses", "runInSeparateProcess", "small",
    "test", "testdox", "testWith", "ticket", "uses"
];
foreach ($whitelist as $v) {
    AnnotationReader::addGlobalIgnoredName($v);
}

$reflectionClass = new \ReflectionClass(UserController::class);
$methods = $reflectionClass->getMethods();
print_r($methods);

//$reader = new AnnotationReader();
//缓存 reader, 开启debug 若有修改则会更新，否则需手动删除然后更新
$reader = new FileCacheReader(new AnnotationReader(), "runtime/annotation", true);

foreach ($methods as $method) {
    // 读取Route的注解
    $routeAnnotation = $reader->getMethodAnnotation($method, Route::class);
    echo '========================' . PHP_EOL;
    echo "route: {$routeAnnotation->route}" . PHP_EOL . PHP_EOL;
    echo "method: {$routeAnnotation->method}" . PHP_EOL;
    $param = print_r($routeAnnotation->param, true);
    echo "param: {$param}" . PHP_EOL;
    echo "time: {$routeAnnotation->time}" . PHP_EOL;
    $routeAnnotations[] = $routeAnnotation;
}