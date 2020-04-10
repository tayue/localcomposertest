<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
/**
 *  文档 https://raw.githubusercontent.com/apache/incubator-zipkin-api/master/zipkin2-api.yaml
 *  用 https://editor.swagger.io/?_ga=2.121438216.1860594245.1559118783-1964203828.1559118783 打开
 * Class Span
 * @package http
 */

// 时区需要设置准确，否则zipkin 跟踪错误
ini_set('date.timezone', "Asia/Shanghai");

class Span
{
    /**
     *  必须是16位或32位
     * @var string
     */
    private $spanId;
    /**
     * 名称，随便取，便于区分即可
     * @var string
     *
     */
    private $name;
    /**
     *  必须是16位
     * @var string
     */
    private $traceId;
    /**
     * 必须是16位
     * @var string
     */
    private $parentId;

    /**
     *  必须是16位的数字，不能是科学计数法
     * @var bool|string
     */
    private $timestamp;
    /**
     * 消耗时间，不写系统会计算,但是需要提供 kind
     * @var int
     */
    private $duration;
    private $debug;
    /**
     * [
     *    serviceName: "",
     *    ipv4: "",
     *    ipv6: "",
     *    port: ""
     *  ]
     * @var array
     */
    private $localEndpoint;

    /**
     * [ CLIENT, SERVER, PRODUCER, CONSUMER ]
     * @var string
     */
    private $kind;
    /**
     *  同 $localEndpoint
     * @var array
     */
    private $remoteEndpoint;
    /**
     *
     * [
     *    timestamp: 16位数字,  php的int没有16位，所以需要转化为string
     *    value: string
     *  ]
     * @var array
     */
    private $annotations;
    /**
     * tags 记录任何数组
     * 满足 [string,string] 即可
     * @var array
     */
    private $tags;

    /**
     * Span 数据结构示例.
     */
//  id: "352bff9a74ca9ad2"
//  traceId: "5af7183fb1d4cf5f"
//  parentId: "6b221d5bc9e6496c"
//  name: "get /api"
//  timestamp: 1556604172355737
//  duration: 1431
//  kind: "SERVER"
//  localEndpoint:
//     serviceName: "backend"
//     ipv4: "192.168.99.1"
//     port: 3306
//  remoteEndpoint:
//     ipv4: "172.19.0.2"
//     port: 58648
//  tags:
//     http.method: "GET"
//     http.path: "/api"

    /**
     * Span constructor.
     */
    function __construct()
    {
        $this->name = 'default_service';
        /**
         * 时间，统一生成
         */
        $this->timestamp = $this->decimalNotation(microtime(true) * 1000 * 1000);
        //$this->duration = 100;
        $this->debug = true;
    }

    /**
     * 将科学计数法转化为string型的数字
     * @param $num
     * @return bool|string
     */
    function decimalNotation($num)
    {
        $parts = explode('E', $num);
        if (count($parts) != 2) {
            return $num;
        }
        $exp = abs(end($parts)) + 3;
        $decimal = number_format($num, $exp);
        $decimal = rtrim($decimal, '0');
        return substr(str_replace(",", "", rtrim($decimal, '.')), 0, 16);
    }

    /**
     * @param mixed $spanId
     * @return Span
     */
    public function setSpanId($spanId)
    {
        $this->spanId = $spanId;
        return $this;
    }

    /**
     * @param mixed $name
     * @return Span
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $traceId
     * @return Span
     */
    public function setTraceId($traceId)
    {
        $this->traceId = $traceId;
        return $this;
    }

    /**
     * @param mixed $parentId
     * @return Span
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
        return $this;
    }

    /**
     * @param mixed $timestamp
     * @return Span
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @param mixed $duration
     * @return Span
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @param mixed $debug
     * @return Span
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @param mixed $localEndpoint
     * @return Span
     */
    public function setLocalEndpoint($localEndpoint)
    {
        $this->localEndpoint = $localEndpoint;
        return $this;
    }

    /**
     * @param mixed $kind
     * @return Span
     */
    public function setKind($kind)
    {
        $this->kind = $kind;
        return $this;
    }

    /**
     * @param mixed $remoteEndpoint
     * @return Span
     */
    public function setRemoteEndpoint($remoteEndpoint)
    {
        $this->remoteEndpoint = $remoteEndpoint;
        return $this;
    }

    /**
     * @param mixed $annotations
     * @return Span
     */
    public function setAnnotations($annotations)
    {
        $this->annotations = $annotations;
        return $this;
    }

    /**
     * @param mixed $tags
     * @return Span
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function toArray()
    {
        $spanAsArray = [
            'id' => (string)$this->spanId,
            'name' => $this->name,
            'traceId' => (string)$this->traceId,
            'parentId' => $this->parentId ? (string)$this->parentId : null,
            'timestamp' => $this->timestamp,
            'duration' => $this->duration,
            'debug' => $this->debug,
            'localEndpoint' => $this->localEndpoint,
        ];

        if ($this->kind !== null) {
            $spanAsArray['kind'] = $this->kind;
        }

        if ($this->remoteEndpoint !== null) {
            $spanAsArray['remoteEndpoint'] = $this->remoteEndpoint;
        }

        if (!empty($this->annotations)) {
            $spanAsArray['annotations'] = $this->annotations;
        }

        if (!empty($this->tags)) {
            $spanAsArray['tags'] = $this->tags;
        }

        return $spanAsArray;
    }

    /**
     * @param $payload
     * @param array $options
     * @return bool
     * @throws \Exception
     */
    function send($url, $payload, $options = [])
    {
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $requiredHeaders = [
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($payload),
        ];
        $additionalHeaders = (isset($options['headers']) ? $options['headers'] : []);
        $headers = array_merge($additionalHeaders, $requiredHeaders);
        $formattedHeaders = array_map(function ($key, $value) {
            return $key . ': ' . $value;
        }, array_keys($headers), $headers);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $formattedHeaders);

        if (isset($options['timeout'])) {
            curl_setopt($handle, CURLOPT_TIMEOUT, $options['timeout']);
        }

        if (curl_exec($handle) !== false) {
            $statusCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            curl_close($handle);

            if ($statusCode !== 202) {
                throw new \Exception(
                    sprintf('Reporting of spans failed, status code %d', $statusCode)
                );
            }
            return true;
        } else {
            throw new \Exception(sprintf(
                'Reporting of spans failed: %s, error code %s',
                curl_error($handle),
                curl_errno($handle)
            ));
        }
    }

}

/**
 * 使用方式
 */


function sendSpan($url){

    $trace_id = substr(md5(time()),0,16);  //trace_id 一次请求的标志，traceid相同表示为同一个请求
    $span1_id = substr(md5(time()-3500),0,16);  // 当前span 的id
    $span = new Span();
    $span->setSpanId($span1_id);
    $span->setTraceId($trace_id)->setLocalEndpoint(
        ["serviceName"=>"1111"]                // 设置当前的entpoint
    )->setRemoteEndpoint(
        ["serviceName"=> "2222"]               // 设置远程的entpoint
    )->setTags(
        ['http.code'=>200]                      // 设置tag
     );
    $resp = $span->send($url,json_encode([$span->toArray()]),[]);   //第一个跟踪的上传，本次span结束
    usleep(500);    // 假设去处理其他的事情了
    $span2_id = substr(md5(time()-3400),0,16);  // 第二个span的id
    $span2 = new Span();                        // 模拟依赖关系，进行第二个跟踪的上传
    $span2->setSpanId($span2_id);               // 第二个跟踪的id，注意和第一个不同
    $span2->setTraceId($trace_id)->setParentId($span1_id)->setLocalEndpoint(
        ["serviceName"=>"2222"]                 // 设置服务绑定第一个span
    )->setRemoteEndpoint(
        ["serviceName"=> "3333"]
    );
    $res =  json_encode([$span2->toArray()]);       // 发送第二个跟踪
    $resp = $span->send($url,$res,[]);
    return $resp;
}

$url =  'http://192.168.99.88:9411/api/v2/spans';
$res=sendSpan($url);

if($res){
    echo "success\r\n";
}else{
    echo "faild\r\n";
}

