<?php
/**
原型模式（对象克隆以避免创建对象时的消耗）
1：与工厂模式类似，都是用来创建对象。
2：与工厂模式的实现不同，原型模式是先创建好一个原型对象，然后通过clone原型对象来创建新的对象。这样就免去了类创建时重复的初始化操作。
3：原型模式适用于大对象的创建，创建一个大对象需要很大的开销，如果每次new就会消耗很大，原型模式仅需要内存拷贝即可。
 */

class Canvas{
    private $data;
    function init($width = 20, $height = 10)
    {
        $data = array();
        for($i = 0; $i < $height; $i++)
        {
            for($j = 0; $j < $width; $j++)
            {
                $data[$i][$j] = '*';
            }
        }
        $this->data = $data;
    }
    function rect($x1, $y1, $x2, $y2)
    {
        foreach($this->data as $k1 => $line)
        {
            if ($x1 > $k1 or $x2 < $k1) continue;
            foreach($line as $k2 => $char)
            {
                if ($y1>$k2 or $y2<$k2) continue;
                $this->data[$k1][$k2] = '#';
            }
        }
    }

    function draw(){
        foreach ($this->data as $line){
            foreach ($line as $char){
                echo $char;
            }
            echo "<br>;";
        }
    }
}

/**
$c = new Canvas();
5 $c->init();
 6 / $canvas1 = new Canvas();
 7 // $canvas1->init();
 8 $canvas1 = clone $c;//通过克隆，可以省去init()方法，这个方法循环两百次
 9 //去产生一个数组。当项目中需要产生很多的这样的对象时，就会new很多的对象，那样
10 //是非常消耗性能的。
11 $canvas1->rect(2, 2, 8, 8);
12 $canvas1->draw();
13 echo "-----------------------------------------<br>";
14 // $canvas2 = new Canvas();
15 // $canvas2->init();
16 $canvas2 = clone $c;
17 $canvas2->rect(1, 4, 8, 8);
18 $canvas2->draw();
 */

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
$new_map = clone $map_prototype; //大对象

var_dump($map_prototype);
var_dump($new_map);