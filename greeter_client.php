<?php

require_once __DIR__.'/vendor/autoload.php';
ini_set("display_errors", "On");//打开错误提示
ini_set("error_reporting", E_ALL);//显示所有错误
use Helloworld\HelloReply;
use Helloworld\GreeterClient;
use Helloworld\HelloRequest;
use GrpcClient\HiClient;
use Grpc\Parser;

$name = !empty($argv[1]) ? $argv[1] : 'Swoole';


\Swoole\Coroutine::create(function () use($name){

    $client = new HiClient('192.168.99.88:50051', [
        'credentials' => null,
    ]);

        $request = new HelloRequest();
        $request->setName($name);

    /**
     * @var \Grpc\HiReply $reply
     */
    list($reply, $status) = $client->sayHello($request);

            if($reply instanceof HelloReply){
            $message = $reply->getMessage();
            echo "{$message}--------\n";
        }else{
            echo "faild\r\n";
        }


    $client->close();
    var_dump(memory_get_usage(true));
});



//$client = new \App\Grpc\HiClient('192.168.99.88:9509', [
//    'credentials' => null,
//]);
//
//$request = new \Grpc\HiUser();
//$request->setName('hyperf');
//$request->setSex(1);
//
///**
// * @var \Grpc\HiReply $reply
// */
//list($reply, $status) = $client->sayHello($request);
//
//$message = $reply->getMessage();
//$user = $reply->getUser();
//
//$client->close();
//var_dump(memory_get_usage(true));

//\Swoole\Coroutine::create(function () use($name){
//    try{
//    $greeterClient = new GreeterClient('192.168.99.88:50051');
//
//    $request = new HelloRequest();
//    $request->setName($name);
//    list($reply, $status,$response) = $greeterClient->SayHello($request);
//        var_dump($response);
//        if($reply instanceof HelloReply){
//            $message = $reply->getMessage();
//            echo "{$message}--------\n";
//        }else{
//            echo "faild\r\n";
//        }
//
//  // sleep(3);
//   // $greeterClient->close();
//    }catch (Throwable $e){
//     echo $e->getMessage();
//    }
//});

//go(function () use($name){
//    try{
//
//        $request = new HelloRequest();
//        $request->setName($name);
//        list($reply, $status) = $greeterClient->SayHello($request);
//
//        $message = $reply->getMessage();
//
//
//
//
//        echo "{$message}--------\n";
//        // sleep(3);
//        $greeterClient->closeWait();
//    }catch (Throwable $e){
//        echo $e->getMessage();
//    }
//});

//go(function () {
//    $domain = 'www.zhihu.com';
//    $cli = new Swoole\Coroutine\Http2\Client($domain, 443, true);
//    $cli->set([
//        'timeout' => -1,
//        'ssl_host_name' => $domain
//    ]);
//    $cli->connect();
//    $req = new swoole_http2_request;
//    $req->method = 'POST';
//    $req->path = '/api/v4/answers/300000000/voters';
//    $req->headers = [
//        'host' => $domain,
//        "user-agent" => 'Chrome/49.0.2587.3',
//        'accept' => 'text/html,application/xhtml+xml,application/xml',
//        'accept-encoding' => 'gzip'
//    ];
//    $req->data = '{"type":"up"}';
//    $cli->send($req);
//    $response = $cli->recv();
//    assert(json_decode($response->data)->error->code === 602);
//});


//    list($reply, $status) = $greeterClient->SayHello($request);
//    var_dump($reply);
//    $message = $reply->getMessage();
//    echo "{$message}\n";
//    $greeterClient->close();

