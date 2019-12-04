<?php


ini_set("display_errors", "On");//打开错误提示
ini_set("error_reporting", E_ALL);//显示所有错误

//双向链表:后进先出
$stack = new SplDoublyLinkedList();
/**
 * 可见栈和双链表的区别就是IteratorMode改变了而已，栈的IteratorMode只能为：
 * （1）SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_KEEP （默认值,迭代后数据保存）
 * （2）SplDoublyLinkedList::IT_MODE_FIFO | SplDoublyLinkedList::IT_MODE_KEEP （默认值,迭代后数据保存）
 * （3）SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_DELETE （迭代后数据删除）
 * （4）SplDoublyLinkedList::IT_MODE_FIFO | SplDoublyLinkedList::IT_MODE_DELETE （迭代后数据删除）
 */
$stack->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_DELETE);
$stack->push('a');
$stack->push('b');
$stack->push('c');

echo $stack->pop() . "\r\n"; //尾出
echo $stack->shift() . "\r\n"; //头出
print_r($stack);
echo $stack->push('c') . "\r\n"; //尾进
echo $stack->unshift('a') . "\r\n"; //尾进
print_r($stack); //测试IteratorMode
//$stack->offsetSet(0, 'first');//index 为0的是最后一个元素

foreach ($stack as $item) {
    echo $item . PHP_EOL; // first a
}

print_r($stack); //测试IteratorMode