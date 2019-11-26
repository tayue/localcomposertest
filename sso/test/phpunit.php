<?php
require '../../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

class StackTest extends TestCase
{
    public $haha;
    public function testPushAndPop()
    {
        $stack = [];
        $this->assertEquals(0, count($stack));

        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack)-1]);
        $this->assertEquals(1, count($stack));

        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));


    }

    public function testAssertEmpty()
    {
        $this->assertEmpty([]);
    }

    public function testFailure()
    {
        $this->assertClassHasAttribute('preserveGlobalState', TestCase::class);
    }

    public function testJia(){
        $this->assertEquals(3, sum(1,2));
    }



    public static function jia($a,$b){
        return $a+$b;
    }
}

function sum($a,$b){
    return $a+$b;
}


