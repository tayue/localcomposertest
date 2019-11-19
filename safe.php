<?php



die("ss");
/**
 * Created by PhpStorm.
 * User: hdeng
 * Date: 2019/5/22
 * Time: 14:20
 */

//$array = get_object_vars($test);

//$json= '[{"id":"1","name":"\u5f20\u96ea\u6885","age":"27","subject":"\u8ba1\u7b97\u673a\u79d1\u5b66\u4e0e\u6280\u672f"},{"id":"2","name":"\u5f20\u6c9b\u9716","age":"21","subject":"\u8f6f\u4ef6\u5de5\u7a0b"}]';

//$str='{"id":"1"}';
//
//
//$students = json_encode(array("name"=>'ssss'));
//$str=htmlentities($students,ENT_QUOTES,'UTF-8');
//echo nl2br($str."\n");
//$str=html_entity_decode($str);
//
//password_hash();
//
//echo($str);

//setcookie("name", "123", NULL, NULL, NULL, NULL, false);
//setcookie("password", md5("ssss"), NULL, NULL, NULL, NULL, TRUE);
//session_start(['read_and_close'=>1]);
//session_commit();

// 注意：下列不是完整的代码，只是一个示例

//session_start();
//
//// 检查会话被销毁的时间戳
//if (isset($_SESSION['destroyed'])
//    && $_SESSION['destroyed'] < time() - 300) {
//    // 通常不会发生这种情况。如果发生，那么可能是由于不稳定的网络状况或者被攻击导致的
//    // 移除用户会话中的认证信息
//    remove_all_authentication_flag_from_active_sessions($_SESSION['userid']);
//    throw(new DestroyedSessionAccessException);
//}
//
//$old_sessionid = session_id();
//
//// 设置会话销毁时间戳
//$_SESSION['destroyed'] = time(); // 从 PHP 7.0.0 开始, session_regenerate_id() 会自动保存会话数据
//$_SESSION['aa'] =11;
//// 如果直接调用 session_regenerate_id() 函数可能会导致会话丢失的情况，
//// 参见下面的例程
//session_regenerate_id(); //重建会话
//
//// 新创建的会话不需要时间戳
//unset($_SESSION['destroyed']);
//
//$new_sessionid = session_id();
//
//echo "Old Session: $old_sessionid<br />";
//echo "New Session: $new_sessionid<br />";
//
//print_r($_SESSION);

$list = new SplDoublyLinkedList();
$list->push('a');
$list->push('b');
$list->push('c');
$list->push('d');

echo "FIFO (first In First Out) : \n";
$list->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO); //设置先进先出迭代模式
for ($list->rewind(); $list->valid(); $list->next()) {
    echo $list->current() . "\n";
}

class Hero
{
    public $pre = null;
    public $no;
    public $name;
    public $next = null;

    public function __construct($no = '', $name = '')
    {
        $this->no = $no;
        $this->name = $name;
    }

    static public function addHero($head, $hero)
    {
        $cur = $head;
        $isExist = false;
        //判断目前这个链表是否为空
        if ($cur->next == null) {
            $cur->next = $hero;
            $hero->pre = $cur;
        } else {
            //如果不是空节点，则安排名来添加
            //找到添加的位置
            while ($cur->next != null) {
                if ($cur->next->no > $hero->no) {
                    break;
                } else if ($cur->next->no == $hero->no) {
                    $isExist = true;
                    echo "<br>不能添加相同的编号";
                }
                $cur = $cur->next;
            }
            if (!$isExist) {
                if ($cur->next != null) {
                    $hero->next = $cur->next;
                }
                $hero->pre = $cur;
                if ($cur->next != null) {
                    $hero->next->pre = $hero;
                }
                $cur->next = $hero;
            }
        }
    }

    //遍历
    static public function showHero($head)
    {
        $cur = $head;
        while ($cur->next != null) {
            echo "<br>编号：" . $cur->next->no . "名字：" . $cur->next->name;
            $cur = $cur->next;
        }
    }

    static public function delHero($head, $herono)
    {
        $cur = $head;
        $isFind = false;
        while ($cur != null) {
            if ($cur->no == $herono) {
                $isFind = true;
                break;
            }
            //继续找
            $cur = $cur->next;
        }
        if ($isFind) {
            if ($cur->next != null) {
                $cur->next_pre = $cur->pre;
            }
            $cur->pre->next = $cur->next;
        } else {
            echo "<br>没有找到目标";
        }
    }
}

$head = new Hero();
$hero1 = new Hero(1, '1111');
$hero3 = new Hero(3, '3333');
$hero2 = new Hero(2, '2222');
Hero::addHero($head, $hero1);
Hero::addHero($head, $hero3);
Hero::addHero($head, $hero2);
Hero::showHero($head);
Hero::delHero($head, 2);
Hero::showHero($head);

/**
 * 一致性哈希实现接口
 * Interface ConsistentHash
 */
interface ConsistentHash
{
    //将字符串转为hash值
    public function cHash($str);
    //添加一台服务器到服务器列表中
    public function addServer($server);
    //从服务器删除一台服务器
    public function removeServer($server);
    //在当前的服务器列表中找到合适的服务器存放数据
    public function lookup($key);
}

/**
 * 具体一致性哈希实现
 * author chenqionghe
 * Class MyConsistentHash
 */
class MyConsistentHash implements ConsistentHash
{
    public $serverList = array(); //服务器列列表
    public $virtualPos = array(); //虚拟节点的位置
    public $virtualPosNum = 5;  //每个节点对应5个虚节点
    /**
     * 将字符串转换成32位无符号整数hash值
     * @param $str
     * @return int
     */
    public function cHash($str)
    {
        $str = md5($str);
        return sprintf('%u', crc32($str));
    }
    /**
     * 在当前的服务器列表中找到合适的服务器存放数据
     * @param $key 键名
     * @return mixed 返回服务器IP地址
     */
    public function lookup($key)
    {
        $point = $this->cHash($key);//落点的hash值
        $finalServer = current($this->virtualPos);//先取圆环上最小的一个节点当成结果
        foreach($this->virtualPos as $pos=>$server)
        {
            if($point <= $pos)
            {
                $finalServer = $server;
                break;
            }
        }
        reset($this->virtualPos);//重置圆环的指针为第一个
        return $finalServer;
    }
    /**
     * 添加一台服务器到服务器列表中
     * @param $server 服务器IP地址
     * @return bool
     */
    public function addServer($server)
    {
        if(!isset($this->serverList[$server]))
        {
            for($i=0; $i<$this->virtualPosNum; $i++)
            {
                $pos = $this->cHash($server . '-' . $i);
                $this->virtualPos[$pos] = $server;
                $this->serverList[$server][] = $pos;
            }
            ksort($this->virtualPos,SORT_NUMERIC);
        }
        return TRUE;
    }
    /**
     * 移除一台服务器（循环所有的虚节点，删除值为该服务器地址的虚节点）
     * @param $key
     * @return bool
     */
    public function removeServer($key)
    {
        if(isset($this->serverList[$key]))
        {
            //删除对应虚节点
            foreach($this->serverList[$key] as $pos)
            {
                unset($this->virtualPos[$pos]);
            }
            //删除对应服务器
            unset($this->serverList[$key]);
        }
        return TRUE;
    }
}

$hashServer = new MyConsistentHash();
$hashServer->addServer('192.168.1.1');
$hashServer->addServer('192.168.1.2');
$hashServer->addServer('192.168.1.3');
$hashServer->addServer('192.168.1.4');
$hashServer->addServer('192.168.1.5');
$hashServer->addServer('192.168.1.6');
$hashServer->addServer('192.168.1.7');
$hashServer->addServer('192.168.1.8');
$hashServer->addServer('192.168.1.9');
$hashServer->addServer('192.168.1.10');
echo "增加十台服务器192.168.1.1~192.168.1.10<br />";
echo "保存 key1 到 server :".$hashServer->lookup('key1') . '<br />';
echo "保存 key2 到 server :".$hashServer->lookup('key2') . '<br />';
echo "保存 key3 到 server :".$hashServer->lookup('key3') . '<br />';
echo "保存 key4 到 server :".$hashServer->lookup('key4') . '<br />';
echo "保存 key5 到 server :".$hashServer->lookup('key5') . '<br />';
echo "保存 key6 到 server :".$hashServer->lookup('key6') . '<br />';
echo "保存 key7 到 server :".$hashServer->lookup('key7') . '<br />';
echo "保存 key8 到 server :".$hashServer->lookup('key8') . '<br />';
echo "保存 key9 到 server :".$hashServer->lookup('key9') . '<br />';
echo "保存 key10 到 server :".$hashServer->lookup('key10') . '<br />';
echo '<hr />';
echo "移除一台服务器192.168.1.2<br />";
$hashServer->removeServer('192.168.1.2');
echo "保存 key1 到 server :".$hashServer->lookup('key1') . '<br />';
echo "保存 key2 到 server :".$hashServer->lookup('key2') . '<br />';
echo "保存 key3 到 server :".$hashServer->lookup('key3') . '<br />';
echo "保存 key4 到 server :".$hashServer->lookup('key4') . '<br />';
echo "保存 key5 到 server :".$hashServer->lookup('key5') . '<br />';
echo "保存 key6 到 server :".$hashServer->lookup('key6') . '<br />';
echo "保存 key7 到 server :".$hashServer->lookup('key7') . '<br />';
echo "保存 key8 到 server :".$hashServer->lookup('key8') . '<br />';
echo "保存 key9 到 server :".$hashServer->lookup('key9') . '<br />';
echo "保存 key10 到 server :".$hashServer->lookup('key10') . '<br />';
echo '<hr />';
echo "移除一台服务器192.168.1.6<br />";
$hashServer->removeServer('192.168.1.6');
echo "保存 key1 到 server :".$hashServer->lookup('key1') . '<br />';
echo "保存 key2 到 server :".$hashServer->lookup('key2') . '<br />';
echo "保存 key3 到 server :".$hashServer->lookup('key3') . '<br />';
echo "保存 key4 到 server :".$hashServer->lookup('key4') . '<br />';
echo "保存 key5 到 server :".$hashServer->lookup('key5') . '<br />';
echo "保存 key6 到 server :".$hashServer->lookup('key6') . '<br />';
echo "保存 key7 到 server :".$hashServer->lookup('key7') . '<br />';
echo "保存 key8 到 server :".$hashServer->lookup('key8') . '<br />';
echo "保存 key9 到 server :".$hashServer->lookup('key9') . '<br />';
echo "保存 key10 到 server :".$hashServer->lookup('key10') . '<br />';
echo '<hr />';
echo "移除一台服务器192.168.1.8<br />";
$hashServer->removeServer('192.168.1.8');
echo "保存 key1 到 server :".$hashServer->lookup('key1') . '<br />';
echo "保存 key2 到 server :".$hashServer->lookup('key2') . '<br />';
echo "保存 key3 到 server :".$hashServer->lookup('key3') . '<br />';
echo "保存 key4 到 server :".$hashServer->lookup('key4') . '<br />';
echo "保存 key5 到 server :".$hashServer->lookup('key5') . '<br />';
echo "保存 key6 到 server :".$hashServer->lookup('key6') . '<br />';
echo "保存 key7 到 server :".$hashServer->lookup('key7') . '<br />';
echo "保存 key8 到 server :".$hashServer->lookup('key8') . '<br />';
echo "保存 key9 到 server :".$hashServer->lookup('key9') . '<br />';
echo "保存 key10 到 server :".$hashServer->lookup('key10') . '<br />';
echo '<hr />';
echo "移除一台服务器192.168.1.2<br />";
$hashServer->removeServer('192.168.1.2');
echo "保存 key1 到 server :".$hashServer->lookup('key1') . '<br />';
echo "保存 key2 到 server :".$hashServer->lookup('key2') . '<br />';
echo "保存 key3 到 server :".$hashServer->lookup('key3') . '<br />';
echo "保存 key4 到 server :".$hashServer->lookup('key4') . '<br />';
echo "保存 key5 到 server :".$hashServer->lookup('key5') . '<br />';
echo "保存 key6 到 server :".$hashServer->lookup('key6') . '<br />';
echo "保存 key7 到 server :".$hashServer->lookup('key7') . '<br />';
echo "保存 key8 到 server :".$hashServer->lookup('key8') . '<br />';
echo "保存 key9 到 server :".$hashServer->lookup('key9') . '<br />';
echo "保存 key10 到 server :".$hashServer->lookup('key10') . '<br />';
echo '<hr />';
echo "增加一台服务器192.168.1.11<br />";
$hashServer->addServer('192.168.1.11');
echo "保存 key1 到 server :".$hashServer->lookup('key1') . '<br />';
echo "保存 key2 到 server :".$hashServer->lookup('key2') . '<br />';
echo "保存 key3 到 server :".$hashServer->lookup('key3') . '<br />';
echo "保存 key4 到 server :".$hashServer->lookup('key4') . '<br />';
echo "保存 key5 到 server :".$hashServer->lookup('key5') . '<br />';
echo "保存 key6 到 server :".$hashServer->lookup('key6') . '<br />';
echo "保存 key7 到 server :".$hashServer->lookup('key7') . '<br />';
echo "保存 key8 到 server :".$hashServer->lookup('key8') . '<br />';
echo "保存 key9 到 server :".$hashServer->lookup('key9') . '<br />';
echo "保存 key10 到 server :".$hashServer->lookup('key10') . '<br />';
echo '<hr />';


define('sites', [

    'Google',

    'Jser',

    'Taobao'

]);


?>

<script>
    var ps = getCookie("name");
    console.log(ps);

    function getCookie(name) {
        var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)"); //正则匹配
        if (arr = document.cookie.match(reg)) {
            return unescape(arr[2]);
        }
        else {
            return null;
        }
    }

</script>



