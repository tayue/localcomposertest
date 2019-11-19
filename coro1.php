<?php
//function logger($fileName)
//{
//    $fileHandle = fopen($fileName, 'a');
//    // while (true) {
//    $a = [1, 2, 3];
//    $str = (yield yielddfunc($a));
//    var_dump($str);
//    fwrite($fileHandle, yield . "\n");
//    fwrite($fileHandle, yield . "\n");
//
//    // }
//}

function gen() {
    $ret = (yield 'yield1');
    var_dump($ret);
    $ret = (yield 'yield2');
    var_dump($ret);
}

//$gen = gen();
//var_dump($gen->current());     // string(6) "yield1"
//$gen->send('ret1');
//var_dump($gen->send('ret1'));  // string(4) "ret1"   (the first var_dump in gen)
// string(6) "yield2" (the var_dump of the ->send() return value)
//$gen->send('ret2');  // string(4) "ret2"   (again from within gen)
// NULL (the return value of ->send())

function yieldfunc($num)
{
    echo "num {$num}\r\n";
    return $num;
}

function yieldfunc1($num)
{
    echo "num {$num}\r\n";
    return $num;
}

function yieldfunc2($num)
{
    echo "num {$num}\r\n";
    return $num;
}

function getLines($file) {
    $f = fopen($file, 'r');
    try {
        while ($line = fgets($f)) {
            yield $line;
        }
    } finally {
        fclose($f);
    }
}


function yieldFuncs()
{
    $a = [1, 2, 3];
    $str = (yield yieldfunc($a[0]));
    var_dump($str);
    $str = (yield yieldfunc1($a[1]));
    var_dump($str);
    $str = (yield yieldfunc2($a[2]));
    var_dump($str);


}

$f=getLines("./tt.txt");

var_dump($f->current());

$gen=yieldFuncs();
$a=$gen->current();
var_dump($a);
$gen->next();
$a=$gen->current();
var_dump($a);
$gen->next();
$a=$gen->current();
var_dump($a);
$gen->next();
$a=$gen->current();
var_dump($a);


$gen = (function() {
    yield 1;
    yield 2;

    return 3;
})();

foreach ($gen as $val) {
    echo $val."##", PHP_EOL;
}

echo $gen->getReturn(), PHP_EOL;

//
//$a = $logger->current();
//var_dump($a);
//$logger = logger(__DIR__ . '/log');
//var_dump($logger);
//$a = $logger->current();
//var_dump($a);
//$b = $logger->send(date("Y-m-d H:i:s"));
//var_dump($b);

//test.php


die();

//$logger->send(date("Y-m-d H:i:s"));
//$logger->send(date("Y-m-d H:i:s"));



