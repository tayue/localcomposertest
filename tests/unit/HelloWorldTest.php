<?php namespace appTest;

class HelloWorldTest extends \Codeception\Test\Unit
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
    public function testSomeFeature()
    {

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