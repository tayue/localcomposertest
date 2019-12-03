<?php

/**
 *  队列这种数据结构更简单，就像我们生活中排队一样，它的特性是先进先出(FIFO)。
 *  PHP SPL中SplQueue类就是实现队列操作，和栈一样，它也可以继承双链表(SplDoublyLinkedList)轻松实现。
 *
 */

$queue = new SplQueue();

/**
 * 可见队列和双链表的区别就是IteratorMode改变了而已，栈的IteratorMode只能为：
 * （1）SplDoublyLinkedList::IT_MODE_FIFO | SplDoublyLinkedList::IT_MODE_KEEP  （默认值,迭代后数据保存）
 * （2）SplDoublyLinkedList::IT_MODE_FIFO | SplDoublyLinkedList::IT_MODE_DELETE （迭代后数据删除）
 */
$queue->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO | SplDoublyLinkedList::IT_MODE_DELETE);

//SplQueue::enqueue()其实就是 SplDoublyLinkedList::push()
$queue->enqueue('a');
$queue->enqueue('b');
$queue->enqueue('c');

//SplQueue::dequeue()其实就是 SplDoublyLinkedList::shift()
echo $queue->dequeue()."@".PHP_EOL;

foreach($queue as $item) {
    echo $item . PHP_EOL;
}

print_r($queue);