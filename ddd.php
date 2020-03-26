<?php
$num=11.239;
$nums=round($num);
var_dump($nums);
$num=$_REQUEST['num'];
echo $num%30;





$date1=strtotime($date1);

$date1=strtotime($date1);



/**
 * 求两个日期之间相差的天数
 * (针对1970年1月1日之后，求之前可以采用泰勒公式)
 * @param string $day1
 * @param string $day2
 * @return number
 */
function diffBetweenTwoDays ($day1, $day2)
{
    $second1 = strtotime($day1);
    $second2 = strtotime($day2);

    if ($second1 < $second2) {
        $tmp = $second2;
        $second2 = $second1;
        $second1 = $tmp;
    }
    return ($second1 - $second2) / 86400;
}
$date1="2020-02-25 12:33:33";
$date2=date("Y-m-d H:i:s");
$diff = ceil(diffBetweenTwoDays($date1, $date2));
echo $diff."\n";
