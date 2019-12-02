<?php
$attrVal="10444.7mm";
$patten="/^([0-9.]+)(\w+)$/";
$res=preg_match($patten,$attrVal,$match);
if($res){
    $attrMatchVal=$match[1];
    $attrMatchUnit=$match[2];
}else{

}


