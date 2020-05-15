<?php


namespace App\Annotation;

use Framework\SwServer\Annotation\AbstractBean;
use Doctrine\Common\Annotations\Annotation;
use Framework\SwServer\Pool\DiPool;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class BeanOne extends AbstractBean
{
    public $type = 2; //默认为服务类型
    public $name;
    public $classNamespace;
    public $isMake = 0; //是否默认利用注解生成类实例放入容器中

    private const OBJECT_TYPE = 0;
    private const COMPONENT_TYPE = 1;
    private const SERVICE_TYPE = 2;


    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getIsMake()
    {
        return $this->isMake;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function makeObject()
    {
        $obj = null;
        switch ($this->type) {
            case self::COMPONENT_TYPE:
                $obj = DiPool::getInstance()->registerComponent($this->name,$this->classNamespace);
                break;
            case self::SERVICE_TYPE:
                $obj = DiPool::getInstance()->registerService($this->name,$this->classNamespace);
                break;
        }
        return $obj;
    }

    public function getObject()
    {
        switch ($this->type) {
            case self::OBJECT_TYPE:
                $obj = DiPool::getInstance()->getSingleton($this->name);
                break;
            case self::COMPONENT_TYPE:
                $obj = DiPool::getInstance()->getComponent($this->name);
                break;
            case self::SERVICE_TYPE:
                $obj = DiPool::getInstance()->getService($this->name);
                break;
            default:
                $obj = DiPool::getInstance()->getSingleton($this->name);
                break;
        }
        return $obj;
    }

    public function getClassInstance($classNamespace){
        $classObj=DiPool::getInstance()->getSingleton($classNamespace);
        DiPool::getInstance()->getSingletons();
        print_r($classObj);
        die('--------------');
    }

    public function get()
    {
        try {
            $obj = $this->getObject();
            if (!$obj) {
                if ($this->isMake && $this->classNamespace) { //如果对象没有初始化在容器中，需不需要重建
                    $obj = $this->makeObject();
                }
            }
            return $obj;
        } catch (\Throwable $e) {
            return false;
        }
    }
}