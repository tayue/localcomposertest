<?php
ini_set('session.cookie_path', '/');

ini_set('session.cookie_domain', '.sso.com');

ini_set('session.cookie_lifetime', '0');

session_start();


if(!$_SESSION['userInfo']){
    $userInfo=array('id'=>1,'name'=>'tayue','age'=>33,'time'=>date("Y-m-d H:i:s"));
    $_SESSION['userInfo']=$userInfo;
}else{
    $_SESSION['userInfo']['time']=date("Y-m-d H:i:s");
}
print_r($_SESSION);

echo session_id()."_______";