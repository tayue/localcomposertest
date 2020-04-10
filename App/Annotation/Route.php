<?php


namespace App\Annotation;


use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * 创建一个注释类
 *
 * Annotation表示该类是一个注解类
 * Target表示注解生效的范围(ALL,CLASS,METHOD,PROPERTY,ANNOTATION)
 *
 * @Annotation
 * @Target({"METHOD"})
 * 也可以在这里变量声明类型
 * @Attributes({
 *   @Attribute("time", type = "int")
 * })
 */
final class Route
{
    /**
     * @Required()
     * @var string
     */
    public $route;

    /**
     * @Enum({"POST", "GET", "PUT", "DELETE"})
     * @var string
     */
    public $method;

    /**
     * @var array
     */
    public $param;

    public $time;

    public function say()
    {
        echo 'hello';
    }
}