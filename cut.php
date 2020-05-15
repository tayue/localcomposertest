<?php
//截取指定两个字符之间的字符串
function cut($begin,$end,$str){
    $b = mb_strpos($str,$begin) + mb_strlen($begin);
    $e = mb_strpos($str,$end) - $b;
    return mb_substr($str,$b,$e);
}


$str='<p><b>Description:</b></p><p>Two way anti-skid, two-way anti loose, safe and guaranteed;</p><p>Upgrade anti loose and anti rotation lock catch for more secure use;</p><p>Upgraded stainless steel material, rust proof, more durable;</p><p>Double sided cotton pad for hand protection, strong perspiration absorption, antiskid and comfortable;</p><p>Thickened non slip pad, soft and elastic, strong and durable.</p><p><b>Specification:</b></p><p>Product: Telescopic Rings</p><p>Color classification: 1. (72 cm-93 cm); 2. (92 cm-125 cm)</p><p>Material: stainless steel</p><p>Applicable population: General</p><p><b>Package Includes:</b></p><p>1 Piece Telescopic Rings</p>';


$res=cut('Description:','Package Includes:',$str);

echo $res;