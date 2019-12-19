<?php
/**
 * 策略模式
 * 策略模式，将一组特定的行为和算法封装成类，以适应某些特定的上下文环境。
 * eg：假如有一个电商网站系统，针对男性女性用户要各自跳转到不同的商品类目，
 * 并且所有的广告位展示不同的广告。在传统的代码中，都是在系统中加入各种if else的判断，
 * 硬编码的方式。如果有一天增加了一种用户，就需要改写代码。使用策略模式，
 * 如果新增加一种用户类型，只需要增加一种策略就可以。其他所有的地方只需要使用不同的策略就可以。
 * 首先声明策略的接口文件，约定了策略的包含的行为。然后，定义各个具体的策略实现类。
 */

/*
 * 声明策略文件的接口，约定策略包含的行为。
 */

interface UserStrategy
{
    function showAd();

    function showCategory();
}

class FemaleUser implements UserStrategy
{
    function showAd()
    {
        echo "2016冬季女装";
    }

    function showCategory()
    {
        echo "女装";
    }
}

class MaleUser implements UserStrategy
{
    function showAd()
    {
        echo "IPhone6s";
    }

    function showCategory()
    {
        echo "电子产品";
    }
}

class Page
{
    protected $strategy;

    function index()
    {
        echo "AD";
        $this->strategy->showAd();
        echo "<br>";
        echo "Category";
        $this->strategy->showCategory();
        echo "<br>";
    }

    function setStrategy(UserStrategy $strategy)
    {
        $this->strategy = $strategy;
    }
}

$page = new Page();
if (isset($_GET['male'])) {
    $strategy = new MaleUser();
} else {
    $strategy = new FemaleUser();
}
$page->setStrategy($strategy);
$page->index();

/*
总结：
通过以上方式，可以发现，在不同用户登录时显示不同的内容，但是解决了在显示时的硬编码的问题。
如果要增加一种策略，只需要增加一种策略实现类，然后在入口文件中执行判断，传入这个类即可。实现了解耦。
实现依赖倒置和控制反转 （有待理解）
通过接口的方式，使得类和类之间不直接依赖。在使用该类的时候，才动态的传入该接口的一个实现类。
如果要替换某个类，只需要提供一个实现了该接口的实现类，通过修改一行代码即可完成替换。
*/
