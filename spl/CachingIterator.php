<?php
/*** a simple array ***/
$array = array('koala', 'kangaroo', 'wombat', 'wallaby', 'emu', 'kiwi', 'kookaburra', 'platypus');

try {
    /*** 这个类有一个hasNext()方法，用来判断是否还有下一个元素。 ***/
    $object = new CachingIterator(new ArrayIterator($array));
    foreach($object as $value)
    {
        echo $value;
        if($object->hasNext())
        {
            echo ',';
        }
    }
}
catch (Exception $e)
{
    echo $e->getMessage();
}