<?php
/**
 * Created by PhpStorm.
 * User: Sixstar-Peter
 * Date: 2019/6/25
 * Time: 23:13
 */

namespace Six\Core\Route;


class Route
{
    /**
     * Example
     *   GET=>[
     *
     *    [
     *        routePath=>'/index/test',
     *        handel   => App\api\IndexController@index
     *    ]
     *
     *   ],
     *
     *
     *
     *
     * @var
     */
    private  static $route;
    /**
     * 添加一个路由
     */
    public static  function  addRoute($method,$routeInfo){
         self::$route[$method][]=$routeInfo;
    }

    /**
     * 路由分发
     */
    public static  function  dispatch($method,$pathInfo){
        switch ($method){
            case 'GET':
                    foreach (self::$route[$method] as $v){
                     //判断路径是否在注册的路由上
                        if($pathInfo==$v['routePath']){
                            $handle=explode("@",$v['handle']);
                            $class=$handle[0];
                            $method=$handle[1];
                           return (new $class)->$method();
                        }
                    }
                break;
            case 'POST':
                break;
        }
    }

}