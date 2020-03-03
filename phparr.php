<?php
/**
 * Created by PhpStorm.
 * User: hdeng
 * Date: 2020/1/14
 * Time: 10:03
 */
$a = array('r'=>1,2,3,4);
var_dump('a',$a);
$b = array('r'=>5,6,7,8);
var_dump('b',$b);
$c = array('r'=>5,6,7,8,9);
var_dump('c',$c);
var_dump('a+b',$a+$b);
var_dump('a+c', $a+$c);
var_dump('amb', array_merge($a, $b));
var_dump('amc', array_merge($a, $c));

/*结论:用加号合并数组:既考虑数字索引的键值对,也考虑字符串索引的键值对,用前边数组的值覆盖后边的键名相同的值;
用array_merge()合并数组:只考虑字符串索引的键值对,用后边数组的值覆盖掉前面数组中键名相同的值,数字索引的值则不覆盖,同时保留
另外:array_merge()会重排两个数组的数字索引,"+"则不会
*/
