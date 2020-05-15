<?php
$description = "<p><b>Description:</b></p><p>This water drinking jug rack holder is aesthetically attractive and designed with luxury in mind. Comes with a tray holder.</p><p>Highest Quality Thick 100% Steel pipe Rack. Non-rusting or slip, doesn&#39;t scratch any surface, last for decades. Extremely sturdy.</p><p>Electric water coolers take up space and are costly. Nu Aqua jug stand dispenser spigot installs in seconds and is fast flowing. Our water dispenser stand is portable so it is perfect for indoors, offices, outdoor, and virtually anywhere. Tray can hold cup, glass, food, and fruits.(Valve lever must be in up position to prevent leaking)</p><p>Removable, portable, fast set up. Fits 3, 4, 5, 6 gallon water bottle jug, glass, or plastic . Hold the lever to pour water.</p><p>Place it anywhere. 5.5CM crown top/mouth ONLY, Not 4.8cm. Not for threaded/screw tops.</p><p><br></p><p><b>Specification:</b></p><p>Material：Steel Pipe；plastic</p><p>Color：Silver，Black</p><p>Size: about 22.5x28.5x26cm / 8.85x11.02x10.23 inches<br></p><p><br></p><p><b>Package Includes:</b></p><p>1 Piece Stand</p><p>1 Piece Spout</p>
";

//echo $description;

$res = funcGetDescIncludes($description);

getMainDescription($res);

echo "<hr>";

function getBetweenString1($start, $end, $str)
{
    $run = 1;
    $res = array();
    while ($run) {
        $tmpstr = substr($str, strlen($start) + strpos($str, $start), (strlen($str) - strpos($str, $end)) * (-1));
        if ($tmpstr) {
            $res[] = $start . $tmpstr . $end;
            $str = str_replace($start . $tmpstr . $end, "", $str);
        } else {
            $run = 0;
        }
    }
    if ($res) {
        $res = current($res);
    }
    return $res;
}

function getBetweenString($begin,$end,$str){
    $b = mb_strpos($str,$begin) + mb_strlen($begin);
    $e = mb_strpos($str,$end) - $b;
    return mb_substr($str,$b,$e);
}

function funcGetDescIncludes($string)
{

    $string = str_replace(array("<b>", "</b>", "<li>", "<div>", "</div>", "<p>","\n","\t","\r","\r\n","&nbsp;"), array("", "", "", "", "", "","","","","",""), $string);
    $string = str_replace(array('</p>', '<ul>', '</ul>', '</li>', '<strong', '</strong>'), array('<br>', '', '', '<br>', '<b', '</b>'), $string);
    $string = str_replace(array("<BR", "<BR ", "<br /", "<br/"), "<br", $string);



    return $string;
}


function getMainDescription($str)
{
    if (!$str) {
        return "";
    }
    if (!(stristr($str, 'descriptions:') || stristr($str, 'description:') && stristr($str, 'package includes:'))) {
        return "";
    }
    echo $str;
    $specificationStr="";
    $str = str_replace(array("<br>"), "\n", $str);
    $packageStr = "Package Includes:";
    stristr($str, 'description:') && $descStr = "Description:";
    stristr($str, 'descriptions:') && $descStr = "Descriptions:";
    stristr($str, 'specification:') && $specificationStr = "Specification:";

    if($specificationStr){ //如果specification存在
        $descBaseStr = getBetweenString($descStr, $specificationStr, $str);
        $descBaseStr = str_replace(array("{$descStr}", "{$specificationStr}"), array("", ""), $descBaseStr);
        $pdNoteStartPos = strlen($specificationStr)+stripos($str, $specificationStr);
    }else{
        $descBaseStr = getBetweenString($descStr, $packageStr, $str);
        $descBaseStr = str_replace(array("{$descStr}", "{$packageStr}"), array("", ""), $descBaseStr);
        $pdNoteStartPos = strlen($packageStr)+stripos($str, $packageStr);
    }
    $pdNoteStr=substr($str,$pdNoteStartPos);
    $pl_main_description=trim($descBaseStr,"\n");
    $pl_note=trim($pdNoteStr,"\n");
    $data['pl_main_description']=$pl_main_description;
    $data['pl_note']=$pl_note;
    print_r($data);
    return $data;
}