<?php
/**
 * Created by PhpStorm.
 * User: Sixstar-Peter
 * Date: 2019/6/25
 * Time: 20:19
 */

namespace Six;

use App\Api\Controller\IndexController;
use App\Api\Controller\TestController;
use Six\Route\Route;
use Swoole\Http\Server;

class App
{
    public function run()
    {

        $this->init(); //初始化

        $this->loadAnnotations(); //载入路由的注解
        //ctrl+c 中止

        $http = new Server("0.0.0.0", 9501);
        $http->on('request', function ($request, $response) {

            $path_info=$request->server['path_info'];
            $method=$request->server['request_method'];
            $res=\Six\Core\Route\Route::dispatch($method,$path_info);

            $response->end($res);
        });
        $http->start(); //启动服务器

    }

    public function init()
    {
        define('ROOT_PATH', dirname(dirname(__DIR__))); //根目录
        define('APP_PATH', ROOT_PATH . '/application');
    }

    public function loadAnnotations()
    {
        $dirs = glob(APP_PATH . '/Api/Controller/*');
        if (!empty($dirs)) {
            foreach ($dirs as $file) {
                $obj = new TestController();
                $reflect = new \ReflectionClass($obj);
                $classDocComment = $reflect->getDocComment(); //类注解
                preg_match('/@Controller\((.*)\)/i', $classDocComment, $prefix);
                $prefix=str_replace("\"","",explode("=",$prefix[1])[1]); //清除掉引号
                //var_dump($prefix); //专门有个解析类
                //匹配前缀
                foreach ($reflect->getMethods() as $method) {
                    $methodDocComment = $method->getDocComment(); //方法注解
                    preg_match('/@RequestMapping\((.*)\)/i', $methodDocComment, $suffix);
                    $suffix=str_replace("\"","",explode("=",$suffix[1])[1]); //清除掉引号
                    $routeInfo=[
                            'routePath'=>$prefix.'/'.$suffix,
                            'handle' =>$reflect->getName()."@".$method->getName()
                    ];
                    \Six\Core\Route\Route::addRoute('GET',$routeInfo); //添加路由

                }
            }
        }
    }
}