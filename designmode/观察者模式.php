<?php
/**
 * 观察者模式
 * 1：观察者模式(Observer)，当一个对象状态发生变化时，依赖它的对象全部会收到通知，并自动更新。
 * 2：场景:一个事件发生后，要执行一连串更新操作。传统的编程方式，就是在事件的代码之后直接加入处理的逻辑。
 * 当更新的逻辑增多之后，代码会变得难以维护。这种方式是耦合的，侵入式的，增加新的逻辑需要修改事件的主体代码。
 * 3：观察者模式实现了低耦合，非侵入式的通知与更新机制。
 */

interface Observer
{
    function update();//这里就是在事件发生后要执行的逻辑
}

abstract class EventGenerator
{ //观察者抽象类
    private $observers = array();

    function addObserver(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update();
        }
    }
}

class Order extends EventGenerator
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    function createOrder()
    {
        echo "创建订单{$this->id}<br>";
    }
}

class SendEmail implements Observer
{
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    function update()
    {
        echo "订单:{$this->order->id},发送email<br>";
    }
}

class SendMessage implements Observer
{
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    function update()
    {
        echo "订单:{$this->order->id},发送短信<br>";
    }
}

$order = new Order(1);
$order->addObserver(new SendEmail($order));
$order->addObserver(new SendMessage($order));
$order->createOrder();
$order->notify();

/*
当某个事件发生后，需要执行的逻辑增多时，可以以松耦合的方式去增删逻辑。也就是代码中的红色部分，
只需要定义一个实现了观察者接口的类，实现复杂的逻辑，然后在红色的部分加上一行代码即可。这样实现了低耦合。
*/