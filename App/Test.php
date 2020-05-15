<?php

namespace App;

use App\Annotation\BeforeAspect;
use App\Annotation\AfterAspect;
use App\Annotation\ClassAspect;
use App\Aop\AbstractTest;

/**
 * @ClassAspect(type="1")
 */
class Test extends AbstractTest
{

    private $property = "I am a private property!";

    public function show()
    {
        echo "exclute Method show()==>" . date("Y-m-d H:i:S") . "\r\n";
        return 'hello world';
    }

    /**
     * @BeforeAspect()
     * @AfterAspect()
     */
    public function jisuan()
    {
        echo "exclute Method jisuan()==>" . date("Y-m-d H:i:S") . "\r\n";
        return 0;
    }

}