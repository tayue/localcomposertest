<?php
/**
 * Created by PhpStorm.
 * User: hdeng
 * Date: 2019/5/20
 * Time: 11:00
 */

//$data[] = array('volume' => 67, 'edition' => 2);
//$data[] = array('volume' => 86, 'edition' => 1);
//$data[] = array('volume' => 85, 'edition' => 6);
//$data[] = array('volume' => 98, 'edition' => 2);
//$data[] = array('volume' => 86, 'edition' => 6);
//$data[] = array('volume' => 67, 'edition' => 7);
//
//
//// 取得列的列表
//foreach ($data as $key => $row) {
//    $volume[$key]  = $row['volume'];
//    $edition[$key] = $row['edition'];
//}
//
//// 将数据根据 volume 降序排列，根据 edition 升序排列
//// 把 $data 作为最后一个参数，以通用键排序
//array_multisort($volume, SORT_DESC, $edition, SORT_DESC, $data);
//
////
//$a="<p>是是是</p>";
//$str=htmlentities($a,ENT_NOQUOTES,"UTF-8");
//echo nl2br($str."\n");
//$str1=htmlspecialchars($a);
//echo nl2br($str1."\n");
//
//$str2=htmlspecialchars_decode($str1);
//echo $str2;


function mbStrreplace($content,$to_encoding="UTF-8",$from_encoding="UTF-8") {
    $content=mb_convert_encoding($content,$to_encoding,$from_encoding);
    $str=mb_convert_encoding("　",$to_encoding,$from_encoding);
    $content=mb_eregi_replace($str," ",$content);
    $content=mb_convert_encoding($content,$from_encoding,$to_encoding);
    $content=trim($content);
    return $content;
}

function make_semiangle($str)
{
    $arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
        '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
        'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
        'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
        'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
        'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
        'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
        'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
        'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
        'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
        'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
        'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
        'ｙ' => 'y', 'ｚ' => 'z',
        '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[',
        '】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']',
        '‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<',
        '》' => '>',
        '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
        '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',
        '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
        '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"',
        '　' => ' ','＄'=>'$','＠'=>'@','＃'=>'#','＾'=>'^','＆'=>'&','＊'=>'*',
        '＂'=>'"');

    return strtr($str, $arr);
}


$str = "Ｃｏｄｅｂｉｔ.ｃｎ － 聚合小段精华代码";

$c=make_semiangle($str);
var_dump($c);

function myfunction($value,$key)
{
    echo "The key $key has the value $value<br>";
}
$a=array("a"=>"red","b"=>"green","c"=>"blue");
array_walk($a,"myfunction");

