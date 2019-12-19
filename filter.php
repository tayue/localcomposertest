<?php
$array=array(
    'ali_seven_sale_set_start'=>0,
    'ali_seven_sale_set_end'=>0,
    'ali_thirty_sale_set_start'=>'11',
    'ali_thirty_sale_set_end'=>22,
    'ali_thirty_sale_start'=>'2019-08-12',
);

$array=array_filter($array,function($val){
    return is_numeric($val) || (is_string($val) && trim($val));
});

print_r($array);
