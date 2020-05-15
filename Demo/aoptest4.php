<?php
//定义RealSubject和Proxy共同具备的东西
interface Subject{
    function say();
    function run();
}

class RealSubject implements Subject{
    private $name;

    function __construct($name){
        $this->name = $name;
    }

    function say(){
        echo $this->name."在吃饭<br>";
    }
    function run(){
        echo $this->name."在跑步<br>";
    }
}


class Proxy implements Subject{
    private $realSubject = null;
    function __construct(RealSubject $realSubject = null){
        if(empty($realSubject)){
            $this->realSubject = new RealSubject();
        }else{
            $this->realSubject = $realSubject;
        }
    }
    function say(){
        $this->realSubject->say();
    }
    function run(){
        $this->realSubject->run();
    }
}

//测试
$subject = new RealSubject("张三");
$proxy = new Proxy($subject);
$proxy->say();
$proxy->run();