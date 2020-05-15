<?php


//声明一个装饰抽象类
abstract class Component
{
    //定义一个操作
    abstract public function operation();
}

class MyOperation extends Component
{
    public function operation()
    {
        echo '这是要进行的操作' . PHP_EOL;
    }
}

//声明一个装饰品抽象类继承装饰抽象类
abstract class Ornament extends Component
{
    //声明一个受保护的变量用来挂载传入的实例
    protected $mountClass;

    //构造函数中把需要挂载的实例挂载到变量中
    public function setMountClass($mountClass)
    {
        $this->mountClass = $mountClass;
    }

    //定义一个装饰行为,执行被挂载实例的operation()方法
    public function operation()
    {
        if ($this->mountClass != null) {
            $this->mountClass->operation();
        }
    }
}

//定义第一个装饰品
class OrnamentCreateA extends Ornament
{
    //重写父类的装饰行为,但是重点是必须执行一次父类装饰行为
    public function operation()
    {
        //执行完之后就是这个类需要装饰什么东西了，在这里可以装饰在前面，也可以装饰在后，可以随便折腾
        echo '我是一个前置装饰器' . PHP_EOL;
        parent::operation();//执行了父类的装饰行为
    }
}

//定义第一个装饰品,这次来实验一下后置装饰器
class OrnamentCreateB extends Ornament
{
    //重写父类的装饰行为,但是重点是必须执行一次父类装饰行为
    public function operation()
    {
        parent::operation();//执行了父类的装饰行为
        echo '我是一个后置装饰器' . PHP_EOL;
    }
}

$MyOperation = new MyOperation();//实例化你的操作
$OrnamentA = new OrnamentCreateA();//实例化装饰品A
$OrnamentB = new OrnamentCreateB();//实例化装饰品B
//操作的顺序是将你的操作丢给一个装饰器，在装饰第二个的时候直接把第一个装饰器丢进去，以此类推
$OrnamentA->setMountClass($MyOperation);
$OrnamentB->setMountClass($OrnamentA);
$OrnamentB->operation();