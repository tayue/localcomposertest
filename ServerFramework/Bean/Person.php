<?php
/**
 * 演示类
 */
namespace ServerFramework\Bean;

class Person
{
    public $car;
    public $bike;
    public $name;
    public $params2;

    public function __construct(Car $car, $name = 'test name', Bike $bike,callable $param1,array $param2 = [1111,2222])
    {
        $this->car = $car;
        $this->bike = $bike;
        $this->name = $name;
        $this->params2 = $param2;
        echo $param1('Person 类初始化构造方法!!!!!')."--------\r\n";
    }

    public function action()
    {
        return $this->name . ' use car pay for ' . $this->car->pay().' to buy bike to '.$this->bike->ride()."\r\n";
    }
}