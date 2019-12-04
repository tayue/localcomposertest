<?php
/*** a simple array ***/
$animals = array('koala', 'kangaroo', 'wombat', 'wallaby', 'emu', 'NZ'=>'kiwi', 'kookaburra', 'platypus');

class CullingIterator extends FilterIterator{

    /*** The filteriterator takes  a iterator as param: ***/
    public function __construct( Iterator $it ){
        parent::__construct( $it );
    }

    /*** check if key is numeric ***/
    function accept(){
        return is_numeric($this->key());
    }

}/*** end of class ***/
$cull = new CullingIterator(new ArrayIterator($animals));

foreach($cull as $key=>$value)
{
    echo $key.' == '.$value.'<br />';
}


//写一个目录过滤迭代器类

class DirectoryFilterIterator extends FilterIterator{
    public $it;
    /*** The filteriterator takes  a iterator as param: ***/
    public function __construct( Iterator $it ){
        $this->it=$it;
        parent::__construct( $it );
    }

    /*** check if key is numeric ***/
    function accept(){
        return $this->it->isDir() && !$this->it->isDot();
    }
}
$cull = new DirectoryFilterIterator(new DirectoryIterator('./'));

foreach($cull as $key=>$value)
{
    echo $key.' == '.$value.'<br />';
}

