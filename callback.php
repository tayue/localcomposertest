<?php

class Test{
    public function __invoke($a,$b)
    {
        return $a+$b;
    }
}

 $t=new Test();
$b=$t(1,3);
assertTrue($b,4);