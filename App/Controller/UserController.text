<?php

namespace App\Controller;


use App\Annotation\Route;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\FileCacheReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * Class UserController
 * 模拟在User控制其中定义注解路由
 * @author 银酱
 */
class UserController
{
    /**
     * 列表
     * @Route(route="/user/index",method="GET")
     */
    public function index()
    {
        echo __METHOD__;
    }

    /**
     * 注册
     * @Route(route="/user/register",method="POST",param={"token","access"},time=123)
     */
    public function register()
    {
        echo __METHOD__;
    }

    /**
     * 显示
     * @Route(route="/user/info",method="POST")
     */
    public function info()
    {
        echo __METHOD__;
    }

    /**
     * 更新
     * @Route(route="/user/update",method="POST")
     */
    public function update()
    {

    }

    /**
     * 删除
     * @Route(route="/user/info",method="DELETE")
     */
    public function delete()
    {
        echo __METHOD__;
    }
}

