<?php
/**
工厂模式
工厂模式，工厂方法或者类生成对象，而不是在代码中直接new。
使用工厂模式，可以避免当改变某个类的名字或者方法之后，在调用这个类的所有的代码中都修改它的名字或者参数。
 */
class Test1{
    static function test(){
        echo __FILE__;
    }
}

class Factory{
    /*
     * 如果某个类在很多的文件中都new ClassName()，那么万一这个类的名字
     * 发生变更或者参数发生变化，如果不使用工厂模式，就需要修改每一个PHP
     * 代码，使用了工厂模式之后，只需要修改工厂类或者方法就可以了。
     */
    static function createDatabase(){
        $test = new Test1();
        return $test;
    }
}

$test = Factory::createDatabase();
$test->test();
