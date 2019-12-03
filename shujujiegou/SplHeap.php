<?php
/**
 * 堆(Heap)就是为了实现优先队列而设计的一种数据结构，它是通过构造二叉堆(二叉树的一种)实现。根节点最大的堆叫做最大堆或大根堆，
 * 根节点最小的堆叫做最小堆或小根堆。二叉堆还常用于排序(堆排序)。
 * 如下：最小堆（任意节点的优先级不小于它的子节点）
 */

class MySimpleHeap extends SplHeap
{
    //compare()方法用来比较两个元素的大小，绝对他们在堆中的位置
    public function compare( $value1, $value2 ) {
        return ( $value1 - $value2 );
    }
}

$obj = new MySimpleHeap();
$obj->insert( 4 );
$obj->insert( 8 );
$obj->insert( 1 );
$obj->insert( 0 );

echo $obj->top()."__\r\n"; //8
echo $obj->count()."__\r\n"; //4

foreach( $obj as $number ) {
    echo $number."__\r\n";
}


class JupilerLeague extends SplHeap
{
    /**
     * We modify the abstract method compare so we can sort our
     * rankings using the values of a given array
     */
    public function compare($array1, $array2) //自定义比较方法比较成员值
    {
        $values1 = array_values($array1);
        $values2 = array_values($array2);
        if ($values1[0] === $values2[0]) return 0;
        return $values1[0] < $values2[0] ? 1 : -1;
    }
}

// Let's populate our heap here (data of 2009)
$heap = new JupilerLeague();
$heap->insert(array ('AA Gent' => 15));
$heap->insert(array ('Anderlecht' => 20));
$heap->insert(array ('Cercle Brugge' => 11));
$heap->insert(array ('Charleroi' => 12));
$heap->insert(array ('Club Brugge' => 21));
$heap->insert(array ('G. Beerschot' => 15));
$heap->insert(array ('Kortrijk' => 10));
$heap->insert(array ('KV Mechelen' => 18));
$heap->insert(array ('Lokeren' => 10));
$heap->insert(array ('Moeskroen' => 7));
$heap->insert(array ('Racing Genk' => 11));
$heap->insert(array ('Roeselare' => 6));
$heap->insert(array ('Standard' => 20));
$heap->insert(array ('STVV' => 17));
$heap->insert(array ('Westerlo' => 10));
$heap->insert(array ('Zulte Waregem' => 15));

// For displaying the ranking we move up to the first node
//$heap->top();

// Then we iterate through each node for displaying the result
while ($heap->valid()) {
    list ($team, $score) = each ($heap->current());
    echo $team . ': ' . $score . PHP_EOL;
    $heap->next();
}