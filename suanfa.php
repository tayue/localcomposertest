<?php


$arr = array(6, 3, 8, 6, 4, 7, 2, 9, 5, 1);

//快速排序 原理用数组第一个元素依次遍历比较数组中的所有元素小的在左边大的在右边
//依次类推递归实现
function quickSort($array)
{
    $arrayLength = count($array);
    //判断参数是否是一个数组
    if (!is_array($array)) return array(); //递归出口
    if ($arrayLength <= 1) return $array; //递归出口
    $left = $right = array();
    for ($i = 1; $i < $arrayLength; $i++) {
        if ($array[$i] <= $array[0]) { //小的数放在左边
            $left[] = $array[$i];
        } else {
            $right[] = $array[$i];
        }
    }
    $left = quickSort($left);
    $right = quickSort($right);
    $res = array_merge($left, array($array[0]), $right);
    return $res;
}

//二分查找法(注意必须是先排序好的一组数字:从小到大顺序排序)
function binSearch($arr, $search)
{
    $height = count($arr) - 1; //数组的最大下标值
    $low = 0; //从0开始的下标
    while ($low <= $height) {
        $mid = floor(($low + $height) / 2);//获取中间数所在的下标
        if ($arr[$mid] == $search) {
            return $mid;//返回
        } elseif ($arr[$mid] < $search) {//当中间值小于所查值时，则$mid左边的值都小于$search，此时要将$mid赋值给$low
            $low = $mid + 1;
        } elseif ($arr[$mid] > $search) {//中间值大于所查值,则$mid右边的所有值都大于$search,此时要将$mid赋值给$height
            $height = $mid - 1;
        }
    }
    return "查找失败";
}

//冒泡排序
function bubbleSort($arr)
{
    $len = count($arr);
    for ($i = 0; $i < $len; $i++) {
        for ($j = $i + 1; $j < $len; $j++) {
            if ($arr[$i] <= $arr[$j]) { //后面的数大于前面的数时调换位置
                $temp = $arr[$j]; //大
                $arr[$j] = $arr[$i];
                $arr[$i] = $temp;
            }
        }
    }
    return $arr;
}

//选择排序 选择当前遍历的元素为假设最小的值位置，与内层循环中后一个值比较如果发现内层循环中的值比假设值还小那么将小值放在前面的位置大值放在后面的位置
function select_sort($arr)
{
    //$i 当前最小值的位置， 需要参与比较的元素
    for ($i = 0, $len = count($arr); $i < $len - 1; $i++) {
        //先假设最小的值的位置
        $p = $i;
        //$j 当前都需要和哪些元素比较，$i 后边的。
        for ($j = $i + 1; $j < $len; $j++) {
            //$arr[$p] 是 当前已知的最小值
            if ($arr[$p] > $arr[$j]) {
                //比较，发现更小的,记录下最小值的位置；并且在下次比较时，应该采用已知的最小值进行比较。
                $p = $j;
            }
        }
        //已经确定了当前的最小值的位置，保存到$p中。
        //如果发现 最小值的位置与当前假设的位置$i不同，则位置互换即可
        if ($p != $i) {
            $tmp = $arr[$p];
            $arr[$p] = $arr[$i];
            $arr[$i] = $tmp;
        }
    }
    //返回最终结果
    return $arr;
}


//插入排序
function insertSort($arr) {
    $len=count($arr);
    //这个元素 就是从第二个元素开始，到最后一个元素都是这个需要排序的元素
    for($i=1;$i<$len; $i++) {
        $tmp = $arr[$i];
        //内层循环控制，比较并插入
        for($j=$i-1;$j>=0;$j--) {
            if($tmp < $arr[$j]) {
                //发现插入的元素要小，交换位置，将后边的元素与前面的元素互换
                $arr[$j+1] = $arr[$j];
                $arr[$j] = $tmp;
            } else {
                //如果碰到不需要移动的元素，由于是已经排序好是数组，则前面的就不需要再次比较了。
                break;
            }
        }
    }
    return $arr;
}


/**
 * mergeSort 归并排序
 * 是开始递归函数的一个驱动函数
 * @param &$arr array 待排序的数组
 */
function mergeSort(&$arr)
{
    $len = count($arr);//求得数组长度
    mSort($arr, 0, $len - 1);
}

/**
 * 实际实现归并排序的程序
 * @param &$arr array 需要排序的数组
 * @param $left int 子序列的左下标值
 * @param $right int 子序列的右下标值
 */
function mSort(&$arr, $left, $right)
{

    if ($left < $right) {
        //说明子序列内存在多余1个的元素，那么需要拆分，分别排序，合并
        //计算拆分的位置，长度/2 去整
        $center = floor(($left + $right) / 2);
        //递归调用对左边进行再次排序：
        mSort($arr, $left, $center);
        //递归调用对右边进行再次排序
        mSort($arr, $center + 1, $right);
        //合并排序结果
        mergeArray($arr, $left, $center, $right);
    }
}

/**
 * 将两个有序数组合并成一个有序数组
 * @param &$arr , 待排序的所有元素
 * @param $left , 排序子数组A的开始下标
 * @param $center , 排序子数组A与排序子数组B的中间下标，也就是数组A的结束下标
 * @param $right , 排序子数组B的结束下标（开始为$center+1)
 */
function mergeArray(&$arr, $left, $center, $right)
{
    //设置两个起始位置标记
    $a_i = $left;
    $b_i = $center + 1;
    while ($a_i <= $center && $b_i <= $right) {
        //当数组A和数组B都没有越界时
        if ($arr[$a_i] < $arr[$b_i]) {
            $temp[] = $arr[$a_i++];
        } else {
            $temp[] = $arr[$b_i++];
        }
    }
    //判断 数组A内的元素是否都用完了，没有的话将其全部插入到C数组内：
    while ($a_i <= $center) {
        $temp[] = $arr[$a_i++];
    }
    //判断 数组B内的元素是否都用完了，没有的话将其全部插入到C数组内：
    while ($b_i <= $right) {
        $temp[] = $arr[$b_i++];
    }

    //将$arrC内排序好的部分，写入到$arr内：
    for ($i = 0, $len = count($temp); $i < $len; $i++) {
        $arr[$left + $i] = $temp[$i];
    }

}

//do some test:
//$arr = array(4, 7, 6, 3, 9, 5, 8);
//mergeSort($arr);
//print_r($arr);
//
//
////$arr = quickSort($arr);
//$arr = select_sort($arr);
//echo '<pre>';
//print_r($arr);
//$arr=array(7,9,4,2,1);
//$dd=select_sort($arr);
//var_dump($dd);

//算法练习

function kuaisupaixu($arr)
{ //快速排序
    $lenght = count($arr);
    if ($lenght <= 1) {
        return $arr;
    }
    $left = $right = array();
    for ($i = 1; $i < $lenght; $i++) {
        if ($arr[0] > $arr[$i]) {
            $left[] = $arr[$i];
        } else {
            $right[] = $arr[$i];
        }
    }
    $left = kuaisupaixu($left);
    $right = kuaisupaixu($right);
    $arr = array_merge($left, array($arr[0]), $right);
    return $arr;
}


function erfenchazhao($arr, $searchVal)
{ //二分查找法
    $lowIndex = 0;
    $maxIndex = count($arr) - 1;
    while ($lowIndex <= $maxIndex) {
        $middleIndex = floor(($lowIndex + $maxIndex) / 2);
        if ($searchVal == $arr[$middleIndex]) {
            return $middleIndex;
        } else if ($arr[$middleIndex] > $searchVal) {
            $maxIndex = $middleIndex - 1;
        } else if ($arr[$middleIndex] < $searchVal) {
            $lowIndex = $middleIndex + 1;
        }
    }
}

function xuanzhepaixu($arr)
{ //选择排序
    $len = count($arr);
    for ($i = 0; $i < $len; $i++) {
        $minValPos = $i; //假设当前值的位置为最小值位置
        for ($j = $i + 1; $j < $len; $j++) {
            if ($arr[$j] < $arr[$minValPos]) {
                $minValPos = $j; //将较小值那个位置标识下
            }
        }
        if ($i != $minValPos) {
            $temp = $arr[$minValPos];
            $arr[$minValPos] = $arr[$i];
            $arr[$i] = $temp;
        }
    }
    return $arr;
}

function charupaixu($arr)
{ //插入排序
    $len = count($arr);
    for ($i = 1; $i < $len; $i++) {
        $insertVal = $arr[$i];//插入的元素
        for ($j = $i - 1; $j >= 0; $j--) {
            if ($arr[$j] > $insertVal) { //如果发现要插入的元素要小那么互换位置
                $arr[$j + 1] = $arr[$j]; //内层循环中用 $j+1 来代表后一个位置
                $arr[$j] = $insertVal;
            } else {
                //如果碰到不需要移动的元素，由于是已经排序好是数组，则前面的就不需要再次比较了。
                break;
            }
        }
    }
    return $arr;
}


$arr = array(8, 10, 3, 1, 3, 11, 6, 4);
echo '<pre>';
//sort($arr);
print_r($arr);
//$arr=array(1,2,3,4,7,8,9);

function xuanzepaixu($arr)
{
    $len = count($arr);
    for ($cur = 0; $cur < $len; $cur++) {
        $min = $cur; //假设选择当前第一个元素位是当前最小数位置索引 $min 是专门记录最小值位置变量
        //那么接下来内层的循环依次跟当前位置做比较
        for ($j = $cur + 1; $j < $len; $j++) {
            if ($arr[$min] > $arr[$j]) { //如果发现内层循环里还有数比定义的当前位置数还小那么记录当前位置
                $min = $j;
            }
        }
        if ($cur != $j) { //记录完最小位置后互换两边的值
            $tmp = $arr[$min]; //小的
            $arr[$min] = $arr[$cur];
            $arr[$cur] = $tmp;
        }
    }
    return $arr;
}

function quickSorts($arr)
{  //就是拿第一个元素与后面的元素依次比较，小的部分放在前面数组记录，大的放后面数组记录，最后合并
    if (!is_array($arr)) return array(); //递归出口
    $len = count($arr);
    if ($len <= 1) return $arr; //递归出口
    $right = $left = array();
    for ($i = 1; $i < $len; $i++) {
        if ($arr[0] >= $arr[$i]) {
            $left[] = $arr[$i];
        } else {
            $right[] = $arr[$i];
        }
    }
    $left = quickSorts($left);
    $right = quickSorts($right);
    $arrs = array_merge($left, array($arr[0]), $right);
    return $arrs;
}

function charusort($arr){
    $lenght=count($arr);
    //以第二个数开始来内层循环比较
    for($i=1;$i<$lenght;$i++){
        $temp=$arr[$i]; //当前数依次与前面内存循环数值比较
        for ($j=$i-1;$j>=0;$j--){
            if($arr[$j]>$temp){ //如果内层循环前面的数大于后面的数那么交换位置
                $arr[$j+1]=$arr[$j];
                $arr[$j]=$temp;
            }else{
                break;
            }
        }
    }
    return $arr;
}

echo "----\r\n";

$dd = charusort($arr);

var_dump($dd);





