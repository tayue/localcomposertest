<?php

//$str="1 x pack set of Goose Feather (Approx. 50pcs)";
//
//$pos=stripos($str,"set of")+strlen("set of");
//$dd=substr($str,$pos);
//var_dump($dd);
//echo $dd;
$str='<p><b>Description:</b></p>
<p>Material:Round Resin Rhinestone hello world sss<br>Stainless steel screw-on ear tunnel<br>High quality grade stainless steel<br>Available in sizes 10mm/ 0.39 inch;<br></p>
<p><b>Package Includes:</b></p>
<ul><li><p>
		1 x Pair of Ear Plug</p></li></ul>
<p><b>Note:</b></p>
<p>Since the size above is measured by hand, the size of the actual item you received could be slightly different from the size above.<br>Item color displayed in photos may be showing slightly different on your computer monitor since monitors are not calibrated same.<br></p>

';

function getParseMetalsType($matchStr,$description){
    $metalsType="";
    if(stristr($description,"{$matchStr}:")) {
        $pattern = '/'.$matchStr.':(.*)\<br\>/i';
        $res = preg_match($pattern, $description, $match);
        if (!$res) {
            return $metalsType;
        }
        if ($match[1]) {
            $metalsTypeMatch = trim($match[1]);
            if(stristr($metalsTypeMatch,"<br>")){
                $metalsTypeArr=explode("<br>",$metalsTypeMatch);
                $metalsTypeArr=array_filter($metalsTypeArr);print_r($metalsTypeArr);
                $metalsTypeArr && $metalsType= current($metalsTypeArr);
            }
            if(stristr($metalsType," ")){
                $metalsTypeWordsArr=explode(" ",$metalsType);
                (count($metalsTypeWordsArr)>5) && $metalsType='as described';
            }
        }
    }
    return $metalsType;
}
$res=getParseMetalsType("material",$str);
echo $res;





