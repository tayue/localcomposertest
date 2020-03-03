<?php


/**
 * 限流控制
 */
class RateLimit
{
    private $minNum = 60; //单个用户每分访问数
    private $dayNum = 10000; //单个用户每天总的访问量

    public $redis;

    public function __construct()
    {
       $this->getRedisConnection();
    }

    public function getRedisConnection($host="localhost",$port=6379){
        $this->redis = new Redis();
        $this->redis->connect($host, $port, 60);
    }

    public function minLimit($uid)
    {
        $minNumKey = $uid . '_minNum';
        $dayNumKey = $uid . '_dayNum';
        $resMin    = $this->getRedis($minNumKey, $this->minNum, 60);
        $resDay    = $this->getRedis($dayNumKey, $this->dayNum, 86400);
        if (!$resMin['status'] || !$resDay['status']) {
            exit($resMin['msg'] . $resDay['msg']);
        }
    }

    public function getRedis($key, $initNum, $expire)
    {
        $nowtime  = time();
        $result   = ['status' => true, 'msg' => ''];
        $this->redis->watch($key); //redis 乐观锁并发控制
        $limitVal = $this->redis->get($key);
        if ($limitVal) {
            $limitVal = json_decode($limitVal, true);
            $newNum   = min($initNum, ($limitVal['num'] - 1) + (($initNum / $expire) * ($nowtime - $limitVal['time'])));
            if ($newNum > 0) {
                $redisVal = json_encode(['num' => $newNum, 'time' => time()]);
            } else {
                return ['status' => false, 'msg' => '当前时刻令牌消耗完！'];
            }
        } else {
            $redisVal = json_encode(['num' => $initNum, 'time' => time()]);
        }
        $this->redis->multi();
        $this->redis->set($key, $redisVal);
        $rob_result = $this->redis->exec();
        if (!$rob_result) {
            $result = ['status' => false, 'msg' => '访问频次过多！'];
        }
        return $result;
    }
}

$ratelimit=new RateLimit();
$randUid=11;
$ratelimit->minLimit($randUid);
print_r($ratelimit);