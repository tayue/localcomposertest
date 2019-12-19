<?php

ini_set("display_errors", "On");//打开错误提示
ini_set("error_reporting", E_ALL);//显示所有错误

/**
 * 适配器模式:将各种截然不同的函数接口封装成统一的API。
 * PHP中的数据库操作有MySQL,MySQLi,PDO三种，可以用适配器模式统一成一致，使不同的数据库操作，
 * 统一成一样的API。类似的场景还有cache适配器，可以将memcache,redis,file,apc等不同的缓存函数，统一成一致。
 * 首先定义一个接口(有几个方法，以及相应的参数)。然后，有几种不同的情况，就写几个类实现该接口。将完成相似功能的函数，统一成一致的方法
 */
interface IDatabase
{
    function connect($host, $user, $passwd, $dbname);

    function query($sql);

    function close();

    function getConnection();
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
        print_r($res);

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



