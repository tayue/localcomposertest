<?php
$str='<p><b>Description:</b></p>
<p>Handmade colorful round beads alloy leaves combined tassel earrings<br>Behomia style,hollow flower shaped metal hook earrings for women girl bride<br>Exquisite gorgeous beads jewelry make you confident and charm<br>material:plastic,alloy<br>Earring Size(LxW):Appro. 7.6cmx2.1cm/2.99x0.83inch</p>
<p><b>Package Includes:</b></p>
<p>1Pair Earrings<br></p>
';
$pattern='/Materia:(.*)\<br\>/i';
$res=preg_match($pattern,$str,$match);
if($res){
    $productMaterial=$match[1];
}
var_dump($res);

 print_r($match);