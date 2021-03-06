<?php
// GENERATED CODE -- DO NOT EDIT!

// Original file comments:
// Copyright 2015 gRPC authors.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.
//
namespace Helloworld;

use Grpc\Backoff;

/**
 * The greeting service definition.
 */
class GreeterClient extends \Grpc\BaseStub
{

    public static function retry($times, callable $callback, $sleep = 0)
    {
        $backoff = new Backoff($sleep);
        beginning:
        try {
            return $callback();
        } catch (\Throwable $e) {
            if (--$times < 0) {
                throw $e;
            }
            $backoff->sleep();
            goto beginning;
        }
    }

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts = [])
    {
        parent::__construct($hostname, $opts);
    }

    /**
     * Sends a greeting
     * @param \Helloworld\HelloRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Helloworld\HelloReply[]|\Grpc\StringifyAble[]
     */
    public function SayHello(\Helloworld\HelloRequest $argument,
                             $metadata = [], $options = [])
    {

        return $this->_simpleRequest('/helloworld.Greeter/SayHello',
            $argument,
            ['\Helloworld\HelloReply', 'decode'],
            $metadata, $options);
    }

    public function SayHello1($metadata = [], $options = [])
    {
        return $this->_clientStreamRequest('/helloworld.Greeter/SayHello',
            ['\Helloworld\HelloReply', 'decode'],
            $metadata, $options);
    }

}
