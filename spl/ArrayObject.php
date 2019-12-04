<?php


/*** a simple array ***/
$array = array('koala', 'kangaroo', 'wombat', 'wallaby', 'emu', 'kiwi', 'kookaburra', 'platypus');

/*** 这个类可以将Array转化为object。 ***/
$arrayObj = new ArrayObject($array);
print_r($arrayObj);
print_r((array)$arrayObj);
$serializeObject=$arrayObj->serialize();
//echo $serializeObject."##\r\n";
$arrayObj1=$arrayObj->unserialize($serializeObject);
var_dump($arrayObj1);
/*** iterate over the array ***/
for ($iterator = $arrayObj->getIterator();
    /*** check if valid ***/
     $iterator->valid();
    /*** move to the next array member ***/
     $iterator->next()) {
    /*** output the key and current array value ***/
    echo $iterator->key() . ' => ' . $iterator->current() . '<br />';
}

/*
增加一个元素：

$arrayObj->append('dingo');
对元素排序：

$arrayObj->natcasesort();
显示元素的数量：

echo $arrayObj->count();
删除一个元素：

$arrayObj->offsetUnset(5);
某一个元素是否存在：

if ($arrayObj->offsetExists(3))
{
echo 'Offset Exists<br/>';
}
更改某个位置的元素值：

$arrayObj->offsetSet(5, "galah");
显示某个位置的元素值：

echo $arrayObj->offsetGet(4);
*/