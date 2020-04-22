<?php

function getChangeColumNames(){
    $columMarkName=array(
        'csl_ship_priority'=>'物流运输方式优先级',
        'csl_internat_location'=>'国际运输方式能到达的国家地区',
        'csl_type'=>'运输方式类型',
        'csl_status'=>'是否可用',
        'cost_min'=>'最小金额RMB',
        'cost_max'=>'最大金额RMB',
        'weight_min'=>'最小重量KG',
        'weight_max'=>'最大重量KG',
        'is_battery'=>'是否可运电池',
        'is_metal'=>'是否只运金属',
        //'order_price'=>'订单金额临界值',
        //'min_profit_margin'=>'最低利润率',
        'country_id'=>'国家',
    );
    return $columMarkName;
}

function checkOperateCarrierFeeExtendLog($newCarrierFeeExtendInfo,$oldCompareCarrierFeeExtendInfo){
    $collectChangeMessage=array();
    if(!$oldCompareCarrierFeeExtendInfo || !$newCarrierFeeExtendInfo){
        return $collectChangeMessage;
    }
    $changeColumNames=getChangeColumNames();
    $changeColumsArrs = array_diff_assoc($newCarrierFeeExtendInfo, $oldCompareCarrierFeeExtendInfo);
    if(!$changeColumsArrs){ //如果没有变更项那么返回
        return $collectChangeMessage;
    }
    foreach ($newCarrierFeeExtendInfo as $key=>$eachVal){
        if(isset($changeColumNames[$key]) && isset($changeColumsArrs[$key]) && isset($oldCompareCarrierFeeExtendInfo[$key]) && $eachVal!=$oldCompareCarrierFeeExtendInfo[$key]) {
            $collectChangeMessage[] = "{$changeColumNames[$key]}:{$oldCompareCarrierFeeExtendInfo[$key]}=>{$eachVal}</br>";
        }
    }
    return $collectChangeMessage;
}

$a1 = array("csl_ship_priority" => "red", "csl_type" => "green", "is_metal" => "blue");
$a2 = array("csl_ship_priority" => "red", "csl_type" => "green", "is_metal" => "blue1");

$c="abcd";
$d=strstr($c,"bcd");
var_dump($d);
//$collectChangeMessage=checkOperateCarrierFeeExtendLog($a2,$a1);
//
//print_r($collectChangeMessage);





