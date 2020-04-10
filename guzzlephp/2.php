<?php

class Curl
{
    /**
     * http请求,$data参数信息$data = array("a" => 1,"b" => 2) written:yangxingyi
     */
    public static function http($url, $data = array(), $timeOut = 30)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);//设置url
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeOut);
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//成功结果返回,不自动输出
            $out = curl_exec($ch);
            curl_close($ch);
            return $out;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 发起多个http请求,written:yangxingyi
     * $nodes = array(
     * [0]=>array('url' => 'http://www.baidu.com','data' => array('a'=>1,'b'=>2),
     * [1]=>array('url' => 'http://www.baidu.com','data' => array('a'=>3,'b'=>4) )
     */
    public static function MultiCurl($nodes, $timeOut = 30)
    {
        try {
            if (!is_array($nodes)) {
                return 'url not support!';
                exit();
            }

            $mh = curl_multi_init();
            $curlArray = array();
            foreach ($nodes as $key => $info) {
                if (!is_array($info) || !isset($info['url'])) {
                    continue;
                }
                $ch = curl_init();//初始化
                curl_setopt($ch, CURLOPT_URL, $info['url']);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

                $data = isset($info['data']) ? $info['data'] : null;
                if (!empty($data)) {
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                }
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//成功结果返回,不自动输出
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeOut);//超时
                $curlArray[$key] = $ch;
                curl_multi_add_handle($mh, $curlArray[$key]);
            }

            $running = NULL;
            do {
                usleep(100);//1s=100W微妙
                curl_multi_exec($mh, $running);
            } while ($running > 0);

            $res = array();
            foreach ($nodes as $key => $info) {
                $res[$key] = curl_multi_getcontent($curlArray[$key]);
            }
            foreach ($nodes as $key => $info) {
                curl_multi_remove_handle($mh, $curlArray[$key]);
            }
            curl_multi_close($mh);
            return $res;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
