<?php
/**
 * Created by PhpStorm.
 * User: hdeng
 * Date: 2019/6/10
 * Time: 15:39
 */
//echo nl2br(date('d')."__".date('w')."\r\n");

$beginLastweek=mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));

$endLastweek=mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));



	$beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
	$endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));


//上月
$lastmonth_start = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m")-1,1,date("Y")));
$lastmonth_end = date("Y-m-d H:i:s",mktime(23,59,59,date("m") ,0,date("Y")));
//本月
$thismonth_start = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y")));
$thismonth_end = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y")));



//$dateArr['start_time']= date('Y-m-d H:i:s', strtotime("-30 day"));

//$dateArr['end_time']=date('Y-m-d H:i:s', strtotime("-1 day"));
echo nl2br(date('Y-m-d H:i:s', strtotime("-30 day"))."\r\n");

echo nl2br(date('Y-m-d H:i:s')."\r\n");

//echo "<hr/>";
//$beginLastLastweek=date("Y-m-d H:i:s",$beginLastLastweek);
//$endLastLastweek=date("Y-m-d H:i:s",$endLastLastweek);
//echo nl2br($beginLastLastweek."\r\n");
//
//echo nl2br($endLastLastweek."\r\n");


function getCustomerDate($type){ //1:上周 2:上上周 3:30天 4:60天
    $dateArr=array();
    if(!$type){
        return $dateArr;
    }
    switch ($type){
        case 1:
            $dateArr['start_time']=mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
            $dateArr['end_time']=mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
            break;
        case 2:
            $dateArr['start_time']=mktime(0,0,0,date('m'),date('d')-date('w')+1-7-7,date('Y'));
            $dateArr['end_time']=mktime(23,59,59,date('m'),date('d')-date('w')+1-7,date('Y'));
            break;
        case 3:
            $dateArr['start_time']= date('Y-m-d H:i:s', strtotime("-30 day"));

            $dateArr['end_time']=date('Y-m-d H:i:s', strtotime("-1 day"));
            break;



    }

    return $dateArr;

}


