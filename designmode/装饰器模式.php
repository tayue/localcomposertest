<?php
/**
装饰器模式
1：装饰器模式，可以动态的添加修改类的功能
2：一个类提供了一项功能，如果要在修改并添加额外的功能，传统的编程模式，需要写一个子类继承它，并重写实现类的方法
3：使用装饰器模式，仅需要在运行时添加一个装饰器对象即可实现，可以实现最大额灵活性。
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


$Yaoming=new Person("姚明"); //原始的类
$aTai=new Person("A泰斯特");

$pixie=new PiXie();
$waitao=new Waitao();

$pixie->Decorate($Yaoming); //添加一个装饰器来修改原始的类实现动态扩展类不修改原来的具体类
$waitao->Decorate($pixie);
$waitao->Display();

echo "<hr/>";

$qiuxie=new QiuXie();
$tshirt=new Tshirt();

$qiuxie->Decorate($aTai);
$tshirt->Decorate($qiuxie);
$tshirt->Display();