<?php


namespace GrpcClient;

use Helloworld\HelloReply;


class HiClient extends BaseClient
{
    public function sayHello(\Helloworld\HelloRequest $argument)
    {
        return $this->simpleRequest(
            '/helloworld.Greeter/SayHello',
            $argument,
            [HelloReply::class, 'decode']
        );
    }

}