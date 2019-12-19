<?php
ini_set("display_errors", "On");//打开错误提示
ini_set("error_reporting", E_ALL);//显示所有错误

/**
 * 门面模式（Facade）又称外观模式，用于为子系统中的一组接口提供一个一致的界面。门面模式定义了一个高层接口，
 * 这个接口使得子系统更加容易使用：引入门面角色之后，用户只需要直接与门面角色交互，用户与子系统之间的复杂关系由门面角色来实现，
 * 从而降低了系统的耦合度。
 */

/**
 * OsInterface接口
 */
interface OsInterface
{
    /**
     * halt the OS
     */
    public function halt();
}

/**
 * BiosInterface接口
 */
interface BiosInterface
{
    /**
     * execute the BIOS
     */
    public function execute();

    /**
     * wait for halt
     */
    public function waitForKeyPress();

    /**
     * launches the OS
     *
     * @param OsInterface $os
     */
    public function launch(OsInterface $os);

    /**
     * power down BIOS
     */
    public function powerDown();
}


/**
 * 门面类
 */
class Facades
{
    /**
     * @var OsInterface
     */
    protected $os;

    /**
     * @var BiosInterface
     */
    protected $bios;

    /**
     * This is the perfect time to use a dependency injection container
     * to create an instance of this class
     *
     * @param BiosInterface $bios
     * @param OsInterface $os
     */
    public function __construct(BiosInterface $bios, OsInterface $os)
    {
        $this->bios = $bios;
        $this->os = $os;
    }

    /**
     * turn on the system
     */
    public function turnOn()
    {
        $this->bios->execute();
        $this->bios->waitForKeyPress();
        $this->bios->launch($this->os);
    }

    /**
     * turn off the system
     */
    public function turnOff()
    {
        $this->os->halt();
        $this->bios->powerDown();
    }
}

//实际使用

abstract class Facade
{
    /**
     * The application instance being facaded.
     *
     *
     */
    protected static $app;

    /**
     * The resolved object instances.
     *
     * @var array
     */
    protected static $resolvedInstance;

    /**
     * Resolve the facade root instance from the container.
     *
     * @param string|object $name
     * @return mixed
     */
    protected static function resolveFacadeInstance($name)
    {
        if (is_object($name)) {                // 如果$name已经是一个对象，则直接返回该对象
            return $name;
        }

        if (isset(static::$resolvedInstance[$name])) {                // 如果是已经解析过的对象，直接从$resolvedInstance中返回该对象
            return static::$resolvedInstance[$name];
        }

        return static::$resolvedInstance[$name] = static::$app[$name];    // 从容器中寻找$name对象，并放入$resolvedInstance 中以便下次使用
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param string $method
     * @param array $args
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public static function __callStatic($method, $args)            // 魔术方法，当使用Log::error($msg) 的时候会调用该方法
    {
        $instance = static::getFacadeAccessor();
        if (!$instance) {
            throw new RuntimeException('A facade root has not been set.');
        }
        switch (count($args)) {
            case 0:
                return $instance->$method();
            case 1:
                return $instance->$method($args[0]);
            case 2:
                return $instance->$method($args[0], $args[1]);
            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);
            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);
            default:
                return call_user_func_array([$instance, $method], $args);
        }
    }
}

class PeopleFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return new People();
    }
}


class People  //普通类
{
    public $name;
    public $age;

    public function set($name, $age)
    {
        $this->name = $name;
        $this->age = $age;
    }

    public function show($name, $age)
    {
        echo "People name:{$name},age:{$age}\r\n";
    }

}

PeopleFacade::set('tayue', 22);

PeopleFacade::show('tayue', 22);




/**
 * 门面模式对客户屏蔽子系统组件，因而减少了客户处理的对象的数目并使得子系统使用起来更加方便；实现了子系统与客户之间的松耦合关系，
 * 而子系统内部的功能组件往往是紧耦合的，松耦合关系使得子系统的组件变化不会影响到它的客户；如果应用需要，门面模式并不限制客户程序
 * 使用子系统类，因此你可以让客户程序在系统易用性和通用性之间加以选择。 Laravel 中门面模式的使用也很广泛，基本上每个服务容器中注册
 * 的服务提供者类都对应一个门面类。
 */
