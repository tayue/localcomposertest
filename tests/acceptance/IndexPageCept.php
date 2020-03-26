<?php use appTest\AcceptanceTester;
use PHPUnit\Framework\Assert;

$I = new AcceptanceTester($scenario);	//实例化一个测试者，将全局变量$scenario传进去作为构造参数
$I->wantTo('perform actions and see result');	//我想执行一些动作并且看看结果



$I->amOnPage('/');	//我在 / 这个页面
$I->see('hello world!');	//我看得到“我叫KK"这串文字
$I->click('content');	//我击带有“文章“这两个字的链接
$I->seeCurrentUrlEquals('/list.html');	//我看到当前网址是'/article/list.html'
$I->dontSee('我叫KK');	//我不想看到“我叫KK"这串文字





$I->seeElement('.pArcList');	//我看到class="pArcList"的一个元素
$buttonText = $I->grabTextFrom('div.pArcList div.divArea #span');	//我通过 nav li:nth-child(3) a 这个CSS选择器定位到一个元素并捕捉它里面的文本
Assert::assertEquals('Button', $buttonText);	//调用断言模块断言变量
//$I->click('nav li:nth-child(55) a');
//$I->dontSeeCurrentUrlEquals('/article/list.html');	//我不想看到当前的网址是'/article/list.html'

$I->seeInTitle('Http Server sssss !!!');	//我能在title里看到'Http Server  sssss !!!'三个字


////ajax

$I->click('ajax');	//单击进行ajax请求
$I->click('ajax');	//单击进行ajax请求
$I->click('ajax');	//单击进行ajax请求
$I->see('zhangsan');



$I->seeNumberOfElements('div.contentDiv', 6);

$I->wait(100);


//$param = array(
//    'page' => 2,	//假设我们要第2页的数据
//    'type' => 3,	//假设数据有类型~
//);	//这个$param是要异步请求时提交上去的参数
//$I->sendAjaxGetRequest('http://192.168.99.88:9501/home/test/ajax',$param);	//这样其实就相当于 /datalist.php?page=2&type=3
//$I->seeResponseCodeIs(200);	//断言请求后,服务端响应回来的报文状态码应该是200
//////ajax 请求测试 $m['PhpBrowser']; //这样来取出一个模块
//$modules=$I->getModules();
//codecept_debug($modules);
//$browser = $modules['PhpBrowser']; //这样来取出一个模块
//$jsonString = $browser->client->getInternalResponse()->getContent(); //通过模块获取响应正文,就是那串json,但必须转成string(注意我代码后面有toString的调用),否则你会得到一个对象,这框架抽象得挺厉害,连个响应报文内容都是对象
//$jsonArray = json_decode($jsonString, true);
//codecept_debug($jsonArray);
//Assert::assertIsArray($jsonArray);	//断言解码后的类型
//Assert::assertEquals(2, count($jsonArray));	//断言数据个数
