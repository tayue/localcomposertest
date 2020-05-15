<?php namespace appTest;

use Framework\SwServer\Annotation\AnnotationRegister;
use App\Controller\AnnotationDemo;
use App\Annotation\AnnotatedDescription;
use PHPUnit\Framework\TestCase;
use function method_exists;

class HelloWorldTest extends TestCase
{
    /**
     * @var \appTest\UnitTester
     */
    protected $tester;


    
    protected function _before()
    {
    }

    protected function _after()
    {

    }

    // tests
    public function testClassAnnotation()
    {
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
        print_r($annotations);
        $demoAnnotation = $annotations['App'][AnnotationDemo::class] ?? [];
        $this->assertTrue(!empty($demoAnnotation));

        $this->assertTrue(isset($demoAnnotation['reflection']));

        $annoClassName = [
            AnnotatedDescription::class
        ];
        foreach ($demoAnnotation['annotation'] as $anno) {
            $this->assertTrue(in_array(get_class($anno), $annoClassName));
            if ($anno instanceof AnnotatedDescription) {
                 $this->assertEquals($anno->value, '这是一个用于展示Annotation类的例子。');
            }
        }
    }

    public function testMethodAnnotation()
    {
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
        $methodAnnotations = $annotations['App'][AnnotationDemo::class]['methods'] ?? [];

        $this->assertTrue(!empty($methodAnnotations));

        foreach ($methodAnnotations as $methodName => $methodAry) {
            $methodAnnotation = $methodAry['annotation'][0];
            if (!$methodAnnotation instanceof AnnotatedDescription) {
                $this->assertTrue(false);
                continue;
            }
            $this->assertEquals($methodName, $methodAnnotation->desc);
        }

   }

    public function testPropertyAnnotation()
    {
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
        $propAnnotations = $annotations['App'][AnnotationDemo::class]['properties'] ?? [];

        $this->assertTrue(!empty($propAnnotations));

        print_r($propAnnotations);

        foreach ($propAnnotations as $proName => $proAry) {
            echo $proName."\r\n";
            $proAnnotation = $proAry['annotation'][0];
            if (!$proAnnotation instanceof AnnotatedDescription) {
                $this->assertTrue(false);
                continue;
            }
            $this->assertEquals(1,in_array($proName,["property","extra"]));
            $this->assertEquals(1,in_array($proAnnotation->getValue(),["哈哈","啦啦"]) );
        }

    }

    public function testM1(){
        $this->assertTrue(false);	//传的参数不是true，断言失败
    }

    public function testM2(){
        $isOK = true;
        $this->assertTrue($isOK);	//你换汤不换药咋行？不还是传了个false进去？失败！
    }

    public function testM3(){
        $isOK = time() < 0;	//呵呵，当前时间戳肯定不小于0，不成立，于是这个比较运算得到结果是false
        $this->assertTrue($isOK);	//你又换汤不换药了亲！
    }

    public function testM4(){
        $this->assertTrue(time() < 0);	//后果你懂的
    }

    public function testM5(){
        $this->assertTrue(time() > 0);	//好，恭喜你这次成功了！
        $this->assertTrue(false);	//又失败了
    }
}