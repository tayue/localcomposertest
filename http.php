<?php
$uri="http://www.baidu.com/index.php?sss=444";



$parts = parse_url($uri);

print_r($parts);