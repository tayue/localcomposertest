<?php
/**
 * Created by PhpStorm.
 * User: hdeng
 * Date: 2019/5/21
 * Time: 15:16
 */

//1.注册模式
class Register
{
    protected static  $objects;
    function set($alias,$object)//将对象注册到全局的树上
    {
        self::$objects[$alias]=$object;//将对象放到树上
    }
    static function get($name){
        return self::$objects[$name];//获取某个注册到树上的对象
    }
    function _unset($alias)
    {
        unset(self::$objects[$alias]);//移除某个注册到树上的对象。
    }
}

/*
 * 声明策略文件的接口，约定策略包含的行为。
 */
interface UserStrategy
{
    function showAd();
    function showCategory();
}

//==========观察者模式==============

interface Observer{
    function update(); //观察者模式统一处理入口
}

abstract class EventGenerator{
    public $observers=array();
    public function addObserverObject(Observer $observerObj){
        $this->observers[]=$observerObj;
    }

    public function notify(){
        foreach ($this->observers as $eachObserver){
            $eachObserver->update();
        }
    }

}

class CreateOrder extends EventGenerator{
    public function triger(){
        echo "触发下单其他逻辑\n";
    }
}

class SendEmail implements Observer{
    function update(){
        echo nl2br("下单发送邮件\n");
    }
}
class SendMessage implements Observer{
    function update(){
        echo nl2br("下单发送短信\n");
    }
}

$event = new CreateOrder();
$event->addObserverObject(new SendEmail());
$event->addObserverObject(new SendMessage());
$event->triger();
$event->notify();

//=========观察者模式===============



/**组件对象接口
 * Interface IComponent
 */
interface IComponent
{
    function Display();
}

/**待装饰对象
 * Class Person
 */
class Person implements IComponent
{
    private $name;

    function __construct($name)
    {
        $this->name=$name;
    }

    function Display()
    {
        echo "装扮的：{$this->name}<br/>";
    }
}

/**所有装饰器父类
 * Class Clothes
 */
class Clothes implements IComponent
{
    protected $component;

    function Decorate(IComponent $component)
    {
        $this->component=$component;
    }

    function Display()
    {
        if(!empty($this->component))
        {
            $this->component->Display();
        }
    }

}

//------------------------------具体装饰器----------------

class PiXie extends Clothes
{
    function Display()
    {
        echo "皮鞋  ";
        parent::Display();
    }
}

class QiuXie extends Clothes
{
    function Display()
    {
        echo "球鞋  ";
        parent::Display();
    }
}

class Tshirt extends Clothes
{
    function Display()
    {
        echo "T恤  ";
        parent::Display();
    }
}

class Waitao extends Clothes
{
    function Display()
    {
        echo "外套  ";
        parent::Display();
    }
}


$Yaoming=new Person("姚明");
$aTai=new Person("A泰斯特");

$pixie=new PiXie();
$waitao=new Waitao();

$pixie->Decorate($Yaoming);
$waitao->Decorate($pixie);
$waitao->Display();

echo "<hr/>";

$qiuxie=new QiuXie();
$tshirt=new Tshirt();

$qiuxie->Decorate($aTai);
$tshirt->Decorate($qiuxie);
$tshirt->Display();


//抽象原型类
Abstract class Prototype{
    abstract function __clone();
}
//具体原型类
class Map extends Prototype{
    public $width;
    public $height;
    public $sea;
    public function setAttribute(array $attributes){
        foreach($attributes as $key => $val){
            $this->$key = $val;
        }
    }
    public function __clone(){}
}
//海洋类.这里就不具体实现了。
class Sea{}

//使用原型模式创建对象方法如下
//先创建一个原型对象
$map_prototype = new Map;
$attributes = array('width'=>40,'height'=>60,'sea'=>(new Sea));
$map_prototype->setAttribute($attributes);
//现在已经创建好原型对象了。如果我们要创建一个新的map对象只需要克隆一下
$new_map = clone $map_prototype;

var_dump($map_prototype);
var_dump($new_map);






