<?php


namespace Framework\SwServer\Annotation;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Framework\SwServer\Annotation\ComposerHelper;
use Doctrine\Common\Annotations\AnnotationReader;
use Framework\SwServer\Annotation\Contract\AnnotationLoaderInterface;
use Framework\Traits\SingletonTrait;
use Composer\Autoload\ClassLoader;
use ReflectionClass;
use function get_included_files;
use DirectoryIterator;
use FilesystemIterator;
use InvalidArgumentException;
use IteratorIterator;
use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use SplFileInfo;
use function file_exists;
use function sprintf;
use function strpos;
use function substr;


class AnnotationRegister
{
    use SingletonTrait;

    private $loaderClass = 'AnnotationLoader';
    private $includedFiles = [];
    private $loaderClassSuffix = 'php';

    /**
     * @var array
     *
     * @example
     * [
     *    'loadNamespace' => [
     *        'className' => [
     *             'annotation' => [
     *                  new ClassAnnotation(),
     *                  new ClassAnnotation(),
     *                  new ClassAnnotation(),
     *             ]
     *             'reflection' => new ReflectionClass(),
     *             'properties' => [
     *                  'propertyName' => [
     *                      'annotation' => [
     *                          new PropertyAnnotation(),
     *                          new PropertyAnnotation(),
     *                          new PropertyAnnotation(),
     *                      ]
     *                     'reflection' => new ReflectionProperty(),
     *                  ]
     *             ],
     *            'methods' => [
     *                  'methodName' => [
     *                      'annotation' => [
     *                          new MethodAnnotation(),
     *                          new MethodAnnotation(),
     *                          new MethodAnnotation(),
     *                      ]
     *                     'reflection' => new ReflectionFunctionAbstract(),
     *                  ]
     *            ]
     *        ]
     *    ]
     * ]
     */
    private static $annotations = [];

    /**
     * Annotation scan stats
     *
     * @var array
     */
    private static $classStats = [
        'parserNums' => 0,
        'annotationNums' => 0,
        'AnnotationLoaderClass' => 0,
    ];

    /**
     * @var Closure
     */
    private $hanlerCallback;

    /**
     * @var ClassLoader
     */
    private $classLoader;

    /**
     * Only scan namespace. Default is scan all
     *
     * @var array
     */
    private $onlyScanNamespaces = [];

    /**
     * @var array
     *
     * @example
     * [
     *     'namespace',
     *     'namespace2',
     * ]
     */
    private static $restrictedNamespaces = [];

    /**
     * eg. ['Psr\\', 'PHPUnit\\', 'Symfony\\']
     */
    private $restrictedPsr4Prefixes = [];

    private $autoloader;

    /**
     * skip files
     * eg. ['Test.php']
     */
    private $skipFiles = ['Test.php'];

    private static $skipFilenames = [];

    private function __construct($config)
    {
        foreach ($config as $setKey => $setVal) {
            $functionName = "set" . ucfirst($setKey);
            if (method_exists($this, $functionName)) {
                $this->$functionName($setVal);
            }
        }
        $this->init($config);
    }

    public function setOnlyScanNamespaces($onlyScanNamespaces)
    {
        $this->onlyScanNamespaces = $onlyScanNamespaces;
    }

    public function setHandlerCallback($hanlerCallback)
    {
        $this->hanlerCallback = $hanlerCallback;
    }

    public function setRestrictedPsr4Prefixes($restrictedPsr4Prefixes)
    {
        $this->restrictedPsr4Prefixes = $restrictedPsr4Prefixes;
    }


    /**
     * Notify operation
     *
     * @param string $type
     * @param mixed ...$target
     */
    private function triggerHandlerCallback(string $type, ...$target): void
    {
        if ($this->hanlerCallback) {
            ($this->hanlerCallback)($type, ...$target);
        }
    }

    private function setAnnotationLoader()
    {
        AnnotationRegistry::registerLoader(function (string $class) {
            if (class_exists($class)) {
                return true;
            }
            return false;
        });
    }

    private function isRestrictedNamespace($restrictedPsr4Prefixes, $ns)
    {
        $isRestrictedNamespace = false;
        foreach ($restrictedPsr4Prefixes as $eachRestrictedPsr4Prefixes) {
            if (strpos($eachRestrictedPsr4Prefixes, $ns) == 0 && strstr($eachRestrictedPsr4Prefixes, $ns)) {
                $isRestrictedNamespace = true;
            }
        }
        return $isRestrictedNamespace;
    }

    public static function addRestrictedNamespace($ns)
    {
        self::$restrictedNamespaces[] = $ns;
    }

    public function load()
    {
        $this->classLoader = ComposerHelper::getClassLoader();
        $this->setAnnotationLoader();
        $this->includedFiles = get_included_files();
        $prefixDirsPsr4 = $this->classLoader->getPrefixesPsr4();
        foreach ($prefixDirsPsr4 as $ns => $paths) {
            if ($this->onlyScanNamespaces && !in_array($ns, $this->onlyScanNamespaces)) {
                $this->triggerHandlerCallback("NotOnlyScanNamespaces", $ns);
                continue;
            }

            if ($this->isRestrictedNamespace($this->restrictedPsr4Prefixes, $ns)) {
                self::addRestrictedNamespace($ns);
                $this->triggerHandlerCallback("RestrictedNamespace", $ns);
                continue;
            }

            foreach ($paths as $path) {
               $loaderFile = sprintf('%s/%s.%s', $path, $this->loaderClass, 'php');
                if (!file_exists($loaderFile)) {
                    $this->triggerHandlerCallback("NotLoaderClassFile", $loaderFile);
                    continue;
                }
                $loaderClass = sprintf('%s%s', $ns, $this->loaderClass);
                if (!class_exists($loaderClass)) {
                    $this->triggerHandlerCallback("NotLoaderClass", $loaderClass);
                    continue;
                }
                $this->autoloader = new $loaderClass();
                if (!($this->autoloader instanceof AnnotationLoaderInterface)) {
                    continue;
                }
                self::$classStats['AnnotationLoaderClass']++;
                $nsPaths = $this->autoloader->getPrefixDirs();
                foreach ($nsPaths as $nameSpace => $nameSpaceDirPath) {
                    if (!class_exists($loaderClass)) {
                        $this->triggerHandlerCallback("NotLoaderClass", $loaderClass);
                        continue;
                    }
                    $this->loadAnnotationClass($nameSpace, $nameSpaceDirPath);
                }

            }

        }
    }

    public function init($config)
    {
        $this->triggerHandlerCallback("Debug", $config);
    }

    private function loadAnnotationClass($nameSpace, $nameSpaceDirPath)
    {
        $iterator = self::recursiveIterator($nameSpaceDirPath);
        /* @var SplFileInfo $splFileInfo */
        foreach ($iterator as $splFileInfo) {
            $filePath = $splFileInfo->getPathname();
            if (is_dir($filePath)) {
                continue;
            }
            $fileName = $splFileInfo->getFilename();
            $extension = $splFileInfo->getExtension();

            // It is skip filename
            if (isset($this->skipFiles[$fileName])) {
                AnnotationRegister::addSkipFilename($fileName);
                $this->triggerHandlerCallback("SkipFile", $fileName);
                continue;
            }

            if ($this->loaderClassSuffix !== $extension || strpos($fileName, '.') === 0) {
                continue;
            }
            $suffix = sprintf('.%s', $this->loaderClassSuffix);
            $pathName = str_replace([$nameSpaceDirPath, '/', $suffix], ['', '\\', ''], $filePath);
            $className = sprintf('%s%s', $nameSpace, $pathName);
            $this->parseAnnotation($nameSpace, $className);

        }
    }

    /**
     * @param string $loadNamespace
     * @param string $className
     * @param array $classAnnotation
     */
    public static function registerAnnotation(string $loadNamespace, string $className, array $classAnnotation): void
    {
        self::$classStats['annotationNums']++;
        self::$annotations[$loadNamespace][$className] = $classAnnotation;
    }

    private function parseAnnotation(string $namespace, string $className)
    {
        $reflectionClass = new ReflectionClass($className);
        // skip abstract
        if ($reflectionClass->isAbstract()) {
            return;
        }
        $classAnnotations = $this->parseClassAnnotation($reflectionClass);
        if ($classAnnotations) {
            self::registerAnnotation($namespace, $className, $classAnnotations);
        }
    }

    private function parseClassAnnotation(ReflectionClass $reflectionClass)
    {
        // Annotation reader
        $reader = new AnnotationReader();
        $className = $reflectionClass->getName();
        $classAnnotations = [];
        $parseClassAnnotations = $reader->getClassAnnotations($reflectionClass);
        // Class annotation
        if (!empty($parseClassAnnotations)) {
            $classAnnotations['annotation'] = $parseClassAnnotations;
            $classAnnotations['reflection'] = $reflectionClass;
        }
        // Property annotation
        $reflectionProperties = $reflectionClass->getProperties();
        foreach ($reflectionProperties as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();
            $propertyAnnotations = $reader->getPropertyAnnotations($reflectionProperty);
            if (!empty($propertyAnnotations)) {
                $classAnnotations['properties'][$propertyName]['annotation'] = $propertyAnnotations;
                $classAnnotations['properties'][$propertyName]['reflection'] = $reflectionProperty;
            }
        }

        // Method annotation
        $reflectionMethods = $reflectionClass->getMethods();
        foreach ($reflectionMethods as $reflectionMethod) {
            $methodName = $reflectionMethod->getName();
            $methodAnnotations = $reader->getMethodAnnotations($reflectionMethod);
            if (!empty($methodAnnotations)) {
                $classAnnotations['methods'][$methodName]['annotation'] = $methodAnnotations;
                $classAnnotations['methods'][$methodName]['reflection'] = $reflectionMethod;
            }
        }
        $parentReflectionClass = $reflectionClass->getParentClass();
        if ($parentReflectionClass !== false) {
            $parentClassAnnotation = $this->parseClassAnnotation($parentReflectionClass);
            if (!empty($parentClassAnnotation)) {
                $classAnnotations['parent'] = $parentClassAnnotation;
            }
        }
        return $classAnnotations;
    }

    /**
     * @return array
     */
    public static function getAnnotations(): array
    {
        return self::$annotations;
    }


    /**
     * 目录文件递归迭代器
     * @param string $path
     * @param int $mode
     * @param int $flags
     * @return RecursiveIteratorIterator
     */
    public static function recursiveIterator(
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

    public function getClassLoader()
    {
        return $this->classLoader;
    }


    /**
     * @param string $filename
     */
    public static function addSkipFilename(string $filename): void
    {
        self::$skipFilenames[] = $filename;
    }

    /**
     * @return array
     */
    public static function getClassStats(): array
    {
        return self::$classStats;
    }

    public static function SkipFile($filename)
    {
        echo nl2br("----------SkipFile:{$filename}\r\n");
    }

    public static function RestrictedNamespace($namespace)
    {
        echo nl2br("----------RestrictedNamespace:{$namespace}\r\n");
    }

    public static function NotLoaderClass($className)
    {
        echo nl2br("----------NotLoaderClass:{$className}\r\n");
    }

    public static function NotLoaderClassFile($file)
    {
        echo nl2br("----------NotLoaderClassFile:{$file}\r\n");
    }

    public static function NotOnlyScanNamespaces($ns)
    {
        echo nl2br("!!!!!!!!!!!NotOnlyScanNamespaces:{$ns}\r\n");
    }

    public static function Debug($config)
    {
        echo "---------------------Debug-------------------------\r\n";
        print_r($config);
        echo "---------------------Debug-------------------------\r\n";
    }
}