<?php
interface Monitor{
    public function __construct($class);
    public function __call($name, $arguments);
}

abstract class Event{
    public abstract function before();
    public abstract function after();
}

class MonitorBase implements Monitor{
    private $classes = null;

    public function __construct($class){
        $this->classes=$class;

    }
    public function __call($name, $arguments){
        echo "前置输出";
        echo $name;
        if(method_exists($this->classes,'before')){
            call_user_func(array($this->classes,$name));
        }
        call_user_func(array($this->classes,$name),$arguments);
        if(method_exists($this->classes,'after')){
            call_user_func(array($this->classes,$name));
        }
        echo "后置输出";
    }
}


/**
 * person class
 */
class Person  extends Event{

    /**
     * person class -> function say
     */
    public static function say($i)
    {
        echo "hi-->";
        var_dump($i);
        //return "hi";
    }

    public function before()
    {
       echo __CLASS__.__METHOD__.PHP_EOL;
    }

    public function after()
    {
        echo __CLASS__.__METHOD__.PHP_EOL;
    }
}


class Dog extends Event{
    public function say()
    {
        echo "wang !";
    }

    public function before()
    {
        echo __CLASS__.__METHOD__.PHP_EOL;
    }

    public function after()
    {
        echo __CLASS__.__METHOD__.PHP_EOL;
    }
}


$p = new MonitorBase(new Person());
$p->say("888",'666',array(1,2,3,65,4));

//$d = new MonitorBase(new Dog());
//$d->say();