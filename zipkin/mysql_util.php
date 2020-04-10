<?php

$servername = "192.168.99.88";
$username = "root";
$password = "root";
$dbname = "test";
$port = "3306";
$sockpath = "/tmp/mysql.sock";

function ConnectDB()
{
    global $servername, $username, $password, $dbname, $port, $sockpath;
    $conn = new MySQLi($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "连接成功"."<br>";
    return $conn;
}

function CloseDBConnection($conn)
{
    $conn->close();
}

function QueryDB($conn, $sqlcmd)
{
    sleep(2);
    $result = $conn->query($sqlcmd);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "id: " . $row["id"]. " - Name: " . $row["name"]. " Age: " . $row["age"]. "<br>";
        }
    } else {
        echo "0 结果";
    }
}