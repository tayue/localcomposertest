<?php
$data[] = array('volume' => 67, 'edition' => 2,'sort'=>5);
$data[] = array('volume' => 86, 'edition' => 6,'sort'=>1);
$data[] = array('volume' => 85, 'edition' => 6,'sort'=>4);
$data[] = array('volume' => 98, 'edition' => 2,'sort'=>5);
$data[] = array('volume' => 86, 'edition' => 6,'sort'=>3);
$data[] = array('volume' => 67, 'edition' => 7,'sort'=>2);

// 取得列的列表
foreach ($data as $key => $row) {
    $volume[$key]  = $row['volume'];
    $edition[$key] = $row['edition'];
    $sort[$key] = $row['sort'];
}

// 将数据根据 volume 降序排列，根据 edition 升序排列
// 把 $data 作为最后一个参数，以通用键排序
array_multisort($volume, SORT_DESC, $edition, SORT_ASC, $sort, SORT_ASC, $data);

print_r($data);