<?php

$data = array(
    'haha'=>'haha`',
    'dddd'=>'dddd1'
  );

$data = http_build_query($data);

//$postdata = http_build_query($data);
$options = array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-type:application/x-www-form-urlencoded',
        'content' => $data
        //'timeout' => 60 * 60 // 超时时间（单位:s）
    )
);

$url = "http://192.168.99.88:9501/home/test/post";
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

echo $result;