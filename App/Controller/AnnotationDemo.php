<?php


namespace App\Controller;
use App\Annotation\AnnotatedDescription;

/**
 * @AnnotatedDescription("这是一个用于展示Annotation类的例子。")
 */
class AnnotationDemo
{
    /**
     * @AnnotatedDescription(desc="这个属性必须要为String",type="String", value="哈哈")
     * @var String
     */
    private $property = "I am a private property!";

    /**
     * @AnnotatedDescription(value="啦啦")
     * @var string
     */
    protected $extra;

    /**
     * @AnnotatedDescription(desc="getProperty", type="getter")
     */
    public function getProperty()
    {
        return $this->property;
    }
}