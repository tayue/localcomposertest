<?php
/**
 * Created by PhpStorm.
 * User: hdeng
 * Date: 2019/5/27
 * Time: 17:32
 */
$str="open_door";
$strArr=explode("_",$str);
foreach ($strArr as $key=>$eachVal){
    $strArr[$key]=ucfirst($eachVal);
}
$str=join("",$strArr);
echo $str;
