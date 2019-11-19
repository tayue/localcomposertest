<?php
ini_set('memory_limit', '2048M');
//布隆算法类
class BloomFilter
{
    var $m; # blocksize
    var $n; # number of strings to hash
    var $k; # number of hashing functions
    var $bitset; # hashing block with size m
    function BloomFilter($mInit, $nInit)
    {
        $this->m = $mInit;
        $this->n = $nInit;
        $this->k = ceil(($this->m / $this->n) * log(2));
        $this->bitset = array_fill(0, $this->m, false);
    }
    function hashcode($str)
    {
        $res = array(); #put k hashing bit into $res
        $seed = sprintf('%u', crc32($str));
        mt_srand($seed); // set random seed, or mt_rand wouldn't provide same random arrays at different generation
        for ($i = 0; $i < $this->k; $i++) {
            $res[] = mt_rand(0, $this->m - 1);
        }
        return $res;
    }
    function addKey($key)
    {
        foreach ($this->hashcode($key) as $codebit) {
            $this->bitset[$codebit] = true;
        }
    }
    function existKey($key)
    {
        $code = $this->hashcode($key);
        foreach ($code as $codebit) {
            if ($this->bitset[$codebit] == false) {
                return false;
            }
        }
        return true;
    }
}

//比较布隆算法的效率
 function index($query = "15521118549@iloveyoumorethanicansay"){
//     echo  sprintf('%u', crc32("Thequickbrownfoxjumpedoverthelazydog."));
//   // echo crc32("Thequickbrownfoxjumpedoverthelazydog.");
//    return;
    $len = 1000000;
    $time1 = microtime(true);
    $bf = new BloomFilter($len * 10, $len);
    for ($p = 15521118540; $p < 15521118540 + $len; $p++) {
        $bf->addKey($p . "@iloveyoumorethanicansay");
    }
    echo "共{$len}个数据，加入数组时间" . sprintf("%.10f",(microtime(true) - $time1) * 1000) . "毫秒<br>";
    $time1 = microtime(true);
    if($bf->existKey($query))
        echo $query . "存在于数组中";
    else
        echo $query . "不存在于数组中";
    echo "<br>查询时间" .  sprintf("%.10f",(microtime(true) - $time1) * 1000) . "毫秒<br>";
    $org = array();
    $time1 = microtime(true);
    for ($p = 15521118540; $p < 15521118540 + $len; $p++) {
        $org[] = $p;
    }
    echo "原始处理方法共{$len}个数据，加入数组时间" . sprintf("%.10f",(microtime(true) - $time1) * 1000) . "毫秒<br>";
    $time1 = microtime(true);
    if(in_array($query, $org))
        echo $query . "存在于数组中";
    else
        echo $query . "不存在于数组中";
    echo "<br>查询时间" . sprintf("%.10f",(microtime(true) - $time1) * 1000) . "毫秒";
}


index();