<?php
/**
 * A test class
 *
 * @param foo bar
 * @return baz
 */

namespace App\Controller;

use ServerFramework\Di\Container;
use ServerFramework\Tool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Swoole\Coroutine as SwCoroutine;

/**
 * Class UserController
 * 模拟在User控制其中定义注解路由
 * @Route("/user/", name="userController")
 */
class BlogController
{

    public $marking;
    public $title;
    public $content;
    public $tool;


    /**
     * 匹配 URL: /blog
     * @Route("/blog", name="blog_list")
     */
    public function list()
    {
        die("list");
    }

    public function getMarking()
    {
        return $this->marking;
    }

    public function setMarking($marking)
    {
        return $this->marking = $marking;
    }

    public static function getCurrentState()
    {

    }

    public static function setCurrentState()
    {

    }

    /**依赖注入演示
     * @param Tool $tool
     */
    public function test(Tool $tool){
        $this->tool = $tool;
        $di = Container::getInstance();
        //print_r($di);
        $person = $di['person'];
        //print_r($person);
        print_r($di[Tool::class]);
       // print_r($di->getSingletons());
        echo $this->tool->display();
    }


    public function show($id,Tool $tool)
    {
        //echo "show_______________";
        $this->tool = $tool;
        $di = Container::getInstance();
        //print_r($di->getSingletons());

        echo $this->tool->display();
        print_r($di['person']);
        //print_r($di->getSingletons());
        print_r(get_included_files());

        echo "id:" . $id."\r\n";
        echo "currentSwooleCid:".SwCoroutine::getCid()."\r\n";
        echo 'runtimeMemory:'.round(memory_get_usage()/1024/1024, 2).'MB', '';
    }

    public function article($id, $title)
    {
        echo "article_______________";
        var_dump($id, $title);
    }
}