<?php
require '../vendor/autoload.php';
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
// 该类是一个辅助类，几个属性$value $type $desc将用于描述其他类的属性、方法或者对象的。
/**
 * @Annotation
 * @Target({"ALL"})
 */
class AnnotatedDescription
{
    /**
     * @var mixed
     */
    public $value;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $desc;
}


/**
 * @AnnotatedDescription("这是一个用于展示Annotation类的例子。")
 */
class AnnotationDemo
{
    /**
     * @AnnotatedDescription(desc="这个属性必须要为String",type="String")
     * @var String
     */
    private $property = "I am a private property!";

    /**
     * @AnnotatedDescription(value="啦啦")
     * @var string
     */
    protected $extra;

    /**
     * @AnnotatedDescription(desc="不需要传入参数", type="getter")
     */
    public function getProperty()
    {
        return $this->property;
    }
}

echo "<pre>";
// Lets parse the annotations
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\FileCacheReader;

// 缓存到文件
//$annotationReader = new FileCacheReader(new AnnotationReader(), './cache/annotation', true);
$annotationReader = new AnnotationReader();

// Get class annotation
$reflectionClass = new ReflectionClass(AnnotationDemo::class);
// public function getClassAnnotations(\ReflectionClass $class); 访问类的所有注释
$classAnnotations = $annotationReader->getClassAnnotations($reflectionClass);
echo "========= CLASS ANNOTATIONS =========" . PHP_EOL;
print_r($classAnnotations);

$annotationDemoObject = new AnnotationDemo();
$reflectionObject = new ReflectionObject($annotationDemoObject);
$objectAnnotations = $annotationReader->getClassAnnotations($reflectionObject);
echo "========= OBJECT ANNOTATIONS =========" . PHP_EOL;
print_r($objectAnnotations);

$reflectionProperty = new ReflectionProperty(AnnotationDemo::class, 'property');
// public function getPropertyAnnotations(\ReflectionProperty $property); 访问属性的所有注释
$propertyAnnotations = $annotationReader->getPropertyAnnotations($reflectionProperty);
echo "=========   PROPERTY ANNOTATIONS =========" . PHP_EOL;
print_r($propertyAnnotations);

$reflectionMethod = new ReflectionMethod(AnnotationDemo::class, 'getProperty');
// public function getMethodAnnotations(\ReflectionMethod $method); 访问方法的所有注释
$methodAnnotations = $annotationReader->getMethodAnnotations($reflectionMethod);
echo "=========   Method ANNOTATIONS =========" . PHP_EOL;
print_r($methodAnnotations);


function myException($e)
{
    var_dump('<h3 style="color:red;">myException:'.$e->getMessage().'</h3>');
}

function myError($code, $msg, $file, $line)
{
    var_dump(compact('code', 'msg', 'file', 'line'));
}

function myShutDown()
{
    $data = error_get_last();
    if(is_null($data)){
        var_dump('nothing error');
    } else {
        var_dump('error',$data);
    }
}