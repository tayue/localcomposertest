<?php

$work = new work();
work::forkWorks();
print_r(work::$_works);
print_r(work::$pidMap);

class work {

    public static $_works = array();
    public static $pidMap = array();
    public $workId = 0;
    public $count = 4;
//    public $masterPid = 0;

    public function __construct() {
        $this->workId = spl_object_hash($this);//生成对象的唯一ID
        self::$_works[$this->workId] = $this;
        self::$pidMap[$this->workId] = array();
//        $this->masterPid = posix_getpid();
    }

    public static function forkWorks() {
        foreach (self::$_works as $work) {
            while (count(self::$pidMap[$work->workId]) < $work->count) {
                self::forkOne($work);
            }
        }
    }

    public static function forkOne($work) {
        $pid = pcntl_fork(); //从这句话执行完,就分成两个进程,主进程(pid>0)和子进程(pid=0),两个进程获取到的pid不同
        if ($pid > 0) {
            self::$pidMap[$work->workId][$pid] = $pid;
        } elseif ($pid == 0) {
            self::$_works[$work->workId] = $work;
            self::$pidMap = array();
            while (TRUE){//这里写了死循环
                sleep(1);
                ECHO 1;
            }
            exit;
        } else {

        }
    }

}
