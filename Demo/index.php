<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require_once '../vendor/autoload.php';

use Framework\SwServer\Annotation\AnnotationRegister;

AnnotationRegister::getInstance([
    'restrictedPsr4Prefixes' => ['Psr\\', 'PHPUnit\\', 'Symfony\\','Monolog\\'],
    'onlyScanNamespaces' => ['App\\', 'App\\Annotation\\'],
    'handlerCallback' => function ($type, ...$params) {
        if (method_exists(AnnotationRegister::class, $type)) {
            call_user_func_array([AnnotationRegister::class, $type], $params);
        }
    }
]);
AnnotationRegister::getInstance()->load();
$annotations = AnnotationRegister::getAnnotations();

$aspectAnnotations=AnnotationRegister::getAspectAnnotations();

print_r($annotations);
print_r($aspectAnnotations);
die("--");



print_r($annotations['App']); die("mm");


function ceshi($params)
{
    echo "---------ceshi---------------";
    print_r($params);
}



die("--");

use App\Helper\ComposerHelper;
use App\Controller\BlogController;
use App\Annotation\Route;
use App\Annotation\AnnotatedDescription;
use App\Controller\UserController;
use App\Controller\AnnotationDemo;

// Lets parse the annotations
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\FileCacheReader;


// 系统默认 var、author 标记不会识别
$whitelist = [
    "after", "afterClass", "backupGlobals", "backupStaticAttributes", "before", "beforeClass", "codeCoverageIgnore*",
    "covers", "coversDefaultClass", "coversNothing", "dataProvider", "depends", "doesNotPerformAssertions",
    "expectedException", "expectedExceptionCode", "expectedExceptionMessage", "expectedExceptionMessageRegExp", "group",
    "large", "medium", "preserveGlobalState", "requires", "runTestsInSeparateProcesses", "runInSeparateProcess", "small",
    "test", "testdox", "testWith", "ticket", "uses", "target"
];
foreach ($whitelist as $v) {
    AnnotationReader::addGlobalIgnoredName($v);
}

AnnotationRegistry::registerLoader(function (string $class) {
    echo $class . "----------------------------\r\n";
    if (class_exists($class)) {
        return true;
    }

    return false;
});


$classLoader = ComposerHelper::getClassLoader();
//$includedFiles = get_included_files();

//$prefixDirsPsr4 = $classLoader->getPrefixesPsr4();

$prefixDirsPsr4 = ["App\\" => ['/home/wwwroot/default/localcomposertest/vendor/composer/../../App']];


foreach ($prefixDirsPsr4 as $ns => $paths) {
    echo $ns . "\r\n";
    $loaderClassName = "AutoLoader";
    $loaderClassSuffix = "php";
    // Find package/component loader class
    foreach ($paths as $path) {
        $loaderFile = sprintf('%s/%s.%s', $path, 'AutoLoader', 'php');
        echo $loaderFile . "----------------\r\n";
        if (!file_exists($loaderFile)) {
            echo "not load";
            continue;
        }
        $loaderClass = sprintf('%s%s', $ns, $loaderClassName);
        if (!class_exists($loaderClass)) {
            echo "not load class";
            continue;
        }
        $autoLoader = new $loaderClass();

        $nsPaths = $autoLoader->getPrefixDirs();

        foreach ($nsPaths as $ns => $path) {
            $iterator = recursiveIterator($path);
            /* @var SplFileInfo $splFileInfo */
            foreach ($iterator as $splFileInfo) {
                $filePath = $splFileInfo->getPathname();

                // $splFileInfo->isDir();
                if (is_dir($filePath)) {
                    continue;
                }
                $fileName = $splFileInfo->getFilename();
                $extension = $splFileInfo->getExtension();

                if ($loaderClassSuffix !== $extension || strpos($fileName, '.') === 0) {
                    continue;
                }

                $suffix = sprintf('.%s', $loaderClassSuffix);
                $pathName = str_replace([$path, '/', $suffix], ['', '\\', ''], $filePath);
                $className = sprintf('%s%s', $ns, $pathName);
                $className = AnnotationDemo::class;

                echo $className . " *********\r\n";

                parseAnnotation($ns, $className);
                die("ff");

//                // It is exclude filename
//                if (isset($this->excludedFilenames[$fileName])) {
//                    AnnotationRegister::addExcludeFilename($fileName);
//                    continue;
//                }
//
//                $suffix    = sprintf('.%s', $this->loaderClassSuffix);
//                $pathName  = str_replace([$path, '/', $suffix], ['', '\\', ''], $filePath);
//                $className = sprintf('%s%s', $ns, $pathName);
//
//                // Fix repeat included file bug
//                $autoload = in_array($filePath, $this->includedFiles, true);
//
//                // Will filtering: interfaces and traits
//                if (!class_exists($className, !$autoload)) {
//                    $this->notify('noExistClass', $className);
//                    continue;
//                }
//
//                // Parse annotation
//                $this->parseAnnotation($ns, $className);
            }
        }


        print_r($nsPaths);
        die("ss");
        //$loaderFile = $this->getAnnotationClassLoaderFile($path);
    }

}

function parseAnnotation(string $namespace, string $className): void
{
    echo "namespace:" . $namespace . "\r\n";
    // Annotation reader
    $reflectionClass = new ReflectionClass($className);

    // Fix ignore abstract
    if ($reflectionClass->isAbstract()) {
        return;
    }
    $oneClassAnnotation = parseOneClassAnnotation($reflectionClass);
    print_r($oneClassAnnotation);
    die("fff");
    if (!empty($oneClassAnnotation)) {
        AnnotationRegister::registerAnnotation($namespace, $className, $oneClassAnnotation);
    }
}


function parseOneClassAnnotation(ReflectionClass $reflectionClass): array
{

    // Annotation reader
    $reader = new AnnotationReader();
    $className = $reflectionClass->getName();

    $oneClassAnnotation = [];
    $classAnnotations = $reader->getClassAnnotations($reflectionClass);
    // Register annotation parser
//    foreach ($classAnnotations as $classAnnotation) {
//        if ($classAnnotation instanceof AnnotationParser) {
//            //$this->registerParser($className, $classAnnotation);
//
//            return [];
//        }
//    }

    // Class annotation
    if (!empty($classAnnotations)) {
        $oneClassAnnotation['annotation'] = $classAnnotations;
        $oneClassAnnotation['reflection'] = $reflectionClass;
    }
    // Property annotation
    $reflectionProperties = $reflectionClass->getProperties();
    foreach ($reflectionProperties as $reflectionProperty) {
        $propertyName = $reflectionProperty->getName();
        $propertyAnnotations = $reader->getPropertyAnnotations($reflectionProperty);
        if (!empty($propertyAnnotations)) {
            $oneClassAnnotation['properties'][$propertyName]['annotation'] = $propertyAnnotations;
            $oneClassAnnotation['properties'][$propertyName]['reflection'] = $reflectionProperty;
        }
    }

    // Method annotation
    $reflectionMethods = $reflectionClass->getMethods();
    foreach ($reflectionMethods as $reflectionMethod) {
        $methodName = $reflectionMethod->getName();
        $methodAnnotations = $reader->getMethodAnnotations($reflectionMethod);
        if (!empty($methodAnnotations)) {
            $oneClassAnnotation['methods'][$methodName]['annotation'] = $methodAnnotations;
            $oneClassAnnotation['methods'][$methodName]['reflection'] = $reflectionMethod;
        }
    }

    $parentReflectionClass = $reflectionClass->getParentClass();
    if ($parentReflectionClass !== false) {
        $parentClassAnnotation = parseOneClassAnnotation($parentReflectionClass);
        if (!empty($parentClassAnnotation)) {
            $oneClassAnnotation['parent'] = $parentClassAnnotation;
        }
    }

    return $oneClassAnnotation;
}


function recursiveIterator(  //目录迭代器
    string $path,
    int $mode = RecursiveIteratorIterator::LEAVES_ONLY,
    int $flags = 0
): RecursiveIteratorIterator
{
    if (empty($path) || !file_exists($path)) {
        throw new InvalidArgumentException('File path is not exist! Path: ' . $path);
    }

    $directoryIterator = new RecursiveDirectoryIterator($path);

    return new RecursiveIteratorIterator($directoryIterator, $mode, $flags);
}

//print_r($includedFiles);