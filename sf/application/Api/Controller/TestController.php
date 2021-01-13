<?php
/**
 * Created by PhpStorm.
 * User: Sixstar-Peter
 * Date: 2019/6/25
 * Time: 20:21
 */

namespace App\Api\Controller;

/**
 * Class TestController
 * @Controller(prefix="/test")
 */
class TestController
{
    /**
     *@RequestMapping(route="index")
     */
   public  function  index(){
       return  "控制器方法";
   }
    /**
     *@RequestMapping(route="test")
     */
    public  function  test(){
        return  "控制器方法";
    }
}