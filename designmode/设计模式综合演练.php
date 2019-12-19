<?php

ini_set("display_errors", "On");//打开错误提示
ini_set("error_reporting", E_ALL);//显示所有错误

/**
 * 适配器模式:将各种截然不同的函数接口封装成统一的API。
 * PHP中的数据库操作有MySQL,MySQLi,PDO三种，可以用适配器模式统一成一致，使不同的数据库操作，
 * 统一成一样的API。类似的场景还有cache适配器，可以将memcache,redis,file,apc等不同的缓存函数，统一成一致。
 * 首先定义一个接口(有几个方法，以及相应的参数)。然后，有几种不同的情况，就写几个类实现该接口。将完成相似功能的函数，统一成一致的方法
 */
interface IDatabase  //适配器模式接口
{
    function connect($host, $user, $passwd, $dbname);

    function query($sql);

    function close();

    function getConnection();
}

abstract class BaseModel implements IDatabase  //装饰器设计模式主体类
{
    protected $component;

    function Decorate(IDatabase $component)
    {
        $this->component = $component;
    }

    function query($sql)
    {
        if (!empty($this->component)) {
            $this->component->query($sql);
        }
    }

    function connect($host, $user, $passwd, $dbname)
    {
        if (!empty($this->component)) {
            $this->component->connect($host, $user, $passwd, $dbname);
        }
    }

    function close()
    {
        if (!empty($this->component)) {
            $this->component->close();
        }
    }

    function getConnection()
    {
        if (!empty($this->component)) {
            $this->component->getConnection();
        }
    }
}

class Querys extends BaseModel //具体的装饰器
{

    function query($sql)
    {
        $this->component; //被装饰类实体
        echo __CLASS__ . "=>" . __FUNCTION__ . "--------------\r\n";
        parent::query($sql);
    }
}


class MysqlDriver implements IDatabase
{
    protected $conn;

    public function __construct($host, $user, $passwd, $dbname)
    {
        $this->connect($host, $user, $passwd, $dbname);
    }

    function connect($host, $user, $passwd, $dbname)
    {
        $conn = mysql_connect($host, $user, $passwd);
        mysql_select_db($dbname, $conn);
        $this->conn = $conn;
    }

    function query($sql)
    {
        $res = mysql_query($sql, $this->conn);
        return $res;
    }

    function close()
    {
        mysql_close($this->conn);
    }

    function getConnection()
    {
        return $this->conn;
    }
}

class MsqliDriver implements IDatabase
{
    protected $conn;

    public function __construct($host, $user, $passwd, $dbname)
    {
        $this->connect($host, $user, $passwd, $dbname);
    }

    function connect($host, $user, $passwd, $dbname)
    {
        $this->conn = new mysqli($host, $user, $passwd, $dbname) or die("test 连接失败");
        $res = $this->conn->query("SELECT * FROM t_user");//准备事务2


    }

    function query($sql)
    {
        foreach (mysqli_query($this->conn, $sql) as $row) {
            print_r($row);
        }
        echo "mysqli.................";

    }

    function close()
    {
        mysqli_close($this->conn);
    }

    function getConnection()
    {
        return $this->conn;
    }
}


class PdoDriver implements IDatabase
{
    protected $conn;

    public function __construct($host, $user, $passwd, $dbname)
    {
        $this->connect($host, $user, $passwd, $dbname);
    }

    function connect($host, $user, $passwd, $dbname)
    {
        $dbms = 'mysql';     //数据库类型
        $dsn = "$dbms:host=$host;dbname=$dbname";
        try {
            $this->conn = new PDO($dsn, $user, $passwd); //初始化一个PDO对象
        } catch (PDOException $e) {
            die ("Error!: " . $e->getMessage() . "<br/>");
        }

    }

    function query($sql)
    {
        //查询并全部输出小例子
        $xx = $this->conn->query($sql, PDO::FETCH_ASSOC);
        //一行一行拿数据
        while ($rowx = $xx->fetch()) {
            //输出
            print_r($rowx);
        }
        echo "pdo.................";
    }

    function close()
    {
        unset($this->conn);
    }

    function getConnection()
    {
        return $this->conn;
    }
}


/**
 * 通过以上案例，PHP与MySQL的数据库交互有三套API，在不同的场景下可能使用不同的API，那么开发好的代码，
 * 换一个环境，可能就要改变它的数据库API，那么就要改写所有的代码，使用适配器模式之后，
 * 就可以使用统一的API去屏蔽底层的API差异带来的环境改变之后需要改写代码的问题。
 */
class DbManager implements IDatabase
{
    public $dbDriver;
    protected static $dbObjects;
    public static $instance;
    public $config;

    private function __construct($dbConfig)
    {
        $dbAlias = $dbConfig['type'];
        $this->config = $dbConfig;
        $this->dbDriver = $this->getDriver($dbAlias);
        if (!isset($this->dbDriver)) {
            new Exception("NOT FOUND {$dbAlias} TYPE DB!!!");
        }
    }

    private function __clone()
    {

    }

    public static function getInstance($dbConfig)
    { //单例模式
        if (!(self::$instance instanceof self)) {
            self::$instance = new self($dbConfig);
        }
        return self::$instance;
    }

    public function createDbDriverObject($dbAlias) //工厂方法
    {
        $object = null;
        $host = $this->config['host'];
        $user = $this->config['user'];
        $passwd = $this->config['passwd'];
        $dbname = $this->config['dbname'];
        $query = new Querys();
        switch ($dbAlias) {
            case 'mysql':
                $object = new MysqlDriver($host, $user, $passwd, $dbname);
                $query->Decorate($object);
                return $query;
                break;
            case 'mysqli':
                $object = new MsqliDriver($host, $user, $passwd, $dbname);
                $query->Decorate($object);
                return $query;
                break;
            case 'pdo':
                $object = new PdoDriver($host, $user, $passwd, $dbname);
                $query->Decorate($object);
                return $query;
                break;
            default:
                $object = new PdoDriver($host, $user, $passwd, $dbname);
                $query->Decorate($object);
                return $query;
                break;
        }
    }


    public static function setDriver($alias, $object)
    { //注册树模式
        self::$dbObjects[$alias] = $object;//将对象放到树上
        return self::$dbObjects[$alias];
    }

    public function getDriver($alias)
    { //注册树模式
        if (isset(self::$dbObjects[$alias]) && self::$dbObjects[$alias]) {
            return self::$dbObjects[$alias];//获取某个注册到树上的对象
        } else { //设置好驱动对象并返回
            $dbObject = $this->createDbDriverObject($alias);
            return self::setDriver($alias, $dbObject);
        }
    }

    public static function unsetDriver($alias)
    {
        unset(self::$dbObjects[$alias]);//移除某个注册到树上的对象。
    }

    function connect($host, $user, $passwd, $dbname)
    {
        $this->dbDriver->connect($host, $user, $passwd, $dbname);
    }

    function query($sql)
    {
        return $this->dbDriver->query($sql);
    }

    function close()
    {
        $this->dbDriver->close();
    }

    function getConnection()
    {
        return $this->dbDriver->getConnection();
    }
}


$type = $_REQUEST['type'];

$dbConfig = array(
    'type' => $type,
    'host' => '192.168.99.88',
    'user' => 'root',
    'passwd' => 'root',
    'dbname' => 'test',
);
$rows = DbManager::getInstance($dbConfig)->query("select * from user");

var_dump($rows);


