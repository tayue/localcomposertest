<?php
/**
 * Created by PhpStorm.
 * User: hdeng
 * Date: 2019/5/10
 * Time: 14:36
 */


/*
 * php 利用反射来达到方法属性属性依赖注入功能
 */

class Container{
    public static $containerInstance=[];

    public static function setObject($name,$object){
          self::$containerInstance[$name]=$object;
   }

    public static function getObject($name){
        if(isset(self::$containerInstance[$name])){
            return self::$containerInstance[$name];
        }
    }

    public static function make($name){
        if(!isset(self::$containerInstance[$name])){
            self::setObject($name,new $name());
            return self::getObject($name);
        }else{
            return self::getObject($name);
        }
    }
}
class Util{
    public function display(){
        echo __CLASS__."/".__METHOD__.nl2br("\n");
    }
}

class Functions{
    public function display(){
        echo __CLASS__."/".__METHOD__.nl2br("\n");
    }
}

class DemoController{
    public  function  test(Util $util,Functions $function){
            $util->display();
        $function->display();
    }
}

 function resolveClassMethodDependencies(array $parameters, $instance, $method)
{
    if (! method_exists($instance, $method)) {
        return $parameters;
    }

    return resolveMethodDependencies(
        $parameters, new ReflectionMethod($instance, $method)
    );
}

 function resolveMethodDependencies(array $parameters, ReflectionFunctionAbstract $reflector)
{
    $originalParameters = $parameters;
    foreach ($reflector->getParameters() as $key => $parameter) {
        $instance = transformDependency(
            $parameter, $parameters, $originalParameters
        );
        if (! is_null($instance)) {
            spliceIntoParameters($parameters, $key, $instance);
        }
    }

    return $parameters;
}

 function transformDependency(ReflectionParameter $parameter, $parameters, $originalParameters)
{
    $class = $parameter->getClass();
    if ($class) {
        return $res=Container::make($class->name);
    }
}

 function spliceIntoParameters(array &$parameters, $offset, $value)
{
    array_splice(
        $parameters, $offset, 0, [$value]
    );
}



function runController($params,$class="DemoController",$method="test"){
    $parameters = resolveClassMethodDependencies(
        $params, $class, $method
    );

    if (! method_exists($instance = Container::make($class), $method)) {
        throw new NotFoundHttpException;
    }

    return call_user_func_array([$instance, $method], $parameters);
}
echo '<pre>';

runController(array("Util","Functions"));



