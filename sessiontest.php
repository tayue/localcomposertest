<?php
echo session_status()."\r\n";
if (session_status() !== PHP_SESSION_ACTIVE){
    echo "init session!\r\n";
    session_start();
}else{
    echo "already start session!\r\n";
}
echo session_status()."\r\n";
$res=getallheaders();
echo '<pre>';
print_r($res);
$sessionId=session_id();
file_put_contents("./records.txt",$sessionId." ".$res['User-Agent']."\r\n\r\n",FILE_APPEND);
echo $sessionId;