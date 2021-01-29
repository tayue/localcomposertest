<?php
/**
 * 服务应用实例Demo
 */

namespace ServerFramework\Server;


use Exception;

use FastRoute\Dispatcher;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\Dispatcher\GroupCountBased as DispatcherGroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use ServerFramework\Bean\Person;
use ServerFramework\Di\Container;
use Swoole\Coroutine as SwCoroutine;
use Throwable;

class ServerApp
{

    public $coroutine_id;  //当前协程id

    public function __construct()
    {
        self::pre_init();
    }

    private static function pre_init()
    {
        date_default_timezone_set('PRC');
        self::errorHandlerRegister();
        self::initObject();
        self::setRoute();
    }

    //致命错误回调
    public static function shutdownCallback()
    {
        $e = error_get_last();
        if (!$e) return;
        self::myErrorHandler($e['type'], '<font color="red">Fatal Error</font> ' . $e['message'], $e['file'], $e['line']);
    }

    //错误处理
    protected static function myErrorHandler($errno, $errstr, $errfile, $errline)
    {
        if (php_sapi_name() == "cli") {
            $break = "\r\n";
        } else {
            $break = "<br/>";
        }
        $mes = "[" . date("Y-m-d H:i:s") . "] {$errno} " . $errfile . " " . $errline . " line " . $errstr . $break;
        echo $mes;
    }

    //注册错误事件
    private static function errorHandlerRegister()
    {
        ini_set("display_errors", "On");
        error_reporting(E_ALL | E_STRICT);
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            self::myErrorHandler($errno, $errstr, $errfile, $errline);
        });
        register_shutdown_function(function () {
            self::shutdownCallback();
        });

    }

    /**
     * 初始化协程上下文资源
     */
    public function init()
    {
        $this->coroutine_id = SwCoroutine::getCid();
        ServerManager::getInstance()->coroutine_id = $this->coroutine_id;
        $this->setApp();
    }


    private static function initObject()
    {
        //可以通过配置文件一次性加载类进入容器配置
        $definitions = ['person' => [
            'class' => Person::class,
            'constructor'  => ['name' => 'tayueliuxiang']
        ]];
        Container::getInstance($definitions);
        Container::getInstance()->setSingletonByObject(RouteCollector::class, new RouteCollector(
            new Std, new GroupCountBased
        ));
    }

    /**
     * 设置路由
     */
    private static function setRoute()
    {
        $routeCollector = Container::getInstance()->getSingleton(RouteCollector::class);
        $routeCollector->addRoute(array('GET', 'POST'), '/user/{id:\d+}', 'App\Controller\BlogController@show');
        $routeCollector->addRoute(array('GET', 'POST'), '/test', 'App\Controller\BlogController@test');
        Container::getInstance()->setSingletonByObject(DispatcherGroupCountBased::class, new DispatcherGroupCountBased($routeCollector->getData()));
    }

    /**
     * 设置协程上下文应用实例
     */
    public function setApp()
    {
        if ($this->coroutine_id) {
            $cid = $this->coroutine_id;
        } else {
            $cid = SwCoroutine::getCid();
        }
        if ($cid) {
            ServerManager::$app[$cid] = $this;
        } else {
            ServerManager::$app = $this;
        }
    }

    public function run(\swoole_http_request $request, \swoole_http_response $response)
    {
        $this->init();
        $this->dispatch($request, $response);
    }

    /**路由分发
     * @param \swoole_http_request $request
     * @param \swoole_http_response $response
     * @throws Exception
     */
    public function dispatch(\swoole_http_request $request, \swoole_http_response $response)
    {
        $request_uri = $request->server['request_uri'];
        $httpMethod = $request->server['request_method'];
        $dispatcher = Container::getInstance()->getSingleton(DispatcherGroupCountBased::class);
        $uri = rawurldecode($request_uri);
        $uri = rawurldecode($uri);
        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        try {
            switch ($routeInfo[0]) {
                case Dispatcher::NOT_FOUND:
                    throw new Exception("404 Not Found 没找到对应的方法");
                    break;
                case Dispatcher::METHOD_NOT_ALLOWED:
                    $allowedMethods = $routeInfo[1];
                    throw new Exception("405 Method Not Allowed方法不允许,允许" . join(",", $allowedMethods));
                    break;
                case Dispatcher::FOUND: // 找到对应的方法
                    $handler = $routeInfo[1]; // 获得处理函数
                    $params = $routeInfo[2]; // 获取请求参数
                    list($controller, $action) = explode("@", $handler); //路由实际应用
                    if (class_exists($controller)) {
                        if (method_exists($controller, $action)) {
                            $paramsObjDependencies = Container::getInstance()->resolveClassMethodDependencies($controller, $action);
                            $params = array_merge($params, $paramsObjDependencies);
                            $params = array_values($params);
                            $controllers = new $controller();
                            call_user_func([$controllers, $action], ...$params);
                        } else {
                            new Exception("class no exists action!!!!");
                        }
                    } else {
                        new Exception("class no exists!!!!");
                    }
                    break;
            }

        } catch (Throwable $e) {
            throw new Exception($e->getMessage());
        }


    }

}