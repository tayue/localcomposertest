<?php

$host = 'localhost';
$port = 8066;//mysql端口号，默认为3306，此处为3307
$user = 'root';
$pwd = '123456';
$con = @mysql_connect("{$host}:{$port}", $user, $pwd, true);
if(!$con) {
    die('Database Connect Error:'.mysql_error());
}


mysql_select_db("TESTDB", $con);


mysql_query("INSERT INTO `countries` (`country_id`, `cn_name`, `en_name`, `local_name`, `country_alias`, `country_code`, `sort`, `short_name`, `continent`, `is_common`) VALUES('next value for MYCATSEQ_GLOBAL','阿尔巴尼亚','Albania','',';Albanien;','AL','AEBN',NULL,'N')");



$result = mysql_query("SELECT * FROM countries");

while($row = mysql_fetch_array($result))
{
    echo $row['cn_name'] . " " . $row['en_name'];
    echo "<br />";
}

mysql_close($con);

