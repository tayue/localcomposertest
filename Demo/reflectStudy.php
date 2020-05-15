<?php

use App\Service\OrderService as orderService;
use App\Service\UserService as userService;

class Worker{
    //工人的一些属性
    private $name_;
    private $age_;
    private $salary_;

    //构造方法
    public function __construct($name,$age,$salary){
        $this->name_ = $name;
        $this->age_ = $age;
        $this->salary_ = $salary;
    }
    //输出工人信息的方法
    public function show(){
        echo "年龄".$this->salary_;
        echo "姓名".$this->name_;
        echo "工资".$this->salary_;
    }

    //__toString方法
    public function __toString(){
        return "年龄：".$this->age."，姓名：".$this->name."工资：".$this->salary;
    }

}



//通过类名获取
$workClass_by_classname = new ReflectionClass('Worker');

//通过类的实例对象获取
$w = new Worker("小明",20,20);
$workerClass_by_classinstance = new ReflectionObject($w);

//因为ReflectionObject是ReflectionClass的子类，所以workClass_by_classname的方法，workerClass_by_classinstance同样适用
//下面利用workClass_by_classname对象获取类的一些属性
//获取类名
echo $workClass_by_classname->getName();
//获取类的方法列表
//var_dump($workClass_by_classname->getMethods());
//获取类的属性
//var_dump($workClass_by_classname->getProperties());

//利用反射得到方法，并执行该方法
$worker = $workClass_by_classname->newInstance("小明",20,20);
var_dump($worker);
//$show_method = new ReflectionMethod('Worker','show');
//$show_method->invoke($worker);

//利用反射机制得到属性，并设置值
$property = $workClass_by_classname->getProperty('name_');
print_r($property);
$property->setAccessible(true);
var_dump($property->getValue($worker));

$property->setValue($worker ,'小红22222222222');
var_dump($property->getValue($worker));
print_r($worker);
print_r($workClass_by_classname);
