<?php

ini_set("display_errors", "On");//打开错误提示
ini_set("error_reporting",E_ALL);//显示所有错误

//栈:后进先出
$stack = new SplStack();
/**
 * 可见栈和双链表的区别就是IteratorMode改变了而已，栈的IteratorMode只能为：
 * （1）SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_KEEP （默认值,迭代后数据保存）
 * （2）SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_DELETE （迭代后数据删除）
 */
$stack->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_DELETE);
$stack->push('a');
$stack->push('b');
$stack->push('c');

//$stack->pop(); //出栈

//$stack->offsetSet(0, 'first');//index 为0的是最后一个元素

foreach($stack as $item) {
    echo $item . PHP_EOL; // first a
}

print_r($stack); //测试IteratorMode