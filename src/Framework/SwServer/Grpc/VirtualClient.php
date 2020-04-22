<?php
/*
  +----------------------------------------------------------------------+
  | Swoole-Etcd-Client                                                   |
  +----------------------------------------------------------------------+
  | This source file is subject to version 2.0 of the Apache license,    |
  | that is bundled with this package in the file LICENSE, and is        |
  | available through the world-wide-web at the following url:           |
  | http://www.apache.org/licenses/LICENSE-2.0.html                      |
  | If you did not receive a copy of the Apache2.0 license and are unable|
  | to obtain it through the world-wide-web, please send a note to       |
  | license@swoole.com so we can mail you a copy immediately.            |
  +----------------------------------------------------------------------+
  | Author: Twosee <twose@qq.com>                                        |
  +----------------------------------------------------------------------+
*/

// THIS FILE IS GENERATED BY SCRIPT, DON'T EDIT IT!

namespace Framework\SwServer\Grpc;

class VirtualClient
{

    /**
     * @var Client|null
     */
    private $client;

    public function __construct(string $hostname, array $opts = [])
    {
        if (!empty($opts['use'])) {
            if (!($opts['use'] instanceof Client)) {
                throw new \InvalidArgumentException('parameter use must be instanceof Client');
            }
            $this->use($opts['use']);
        } else {
            $this->client = new Client($hostname, $opts);
        }
    }

    public function getGrpcClient()
    {
        return $this->client;
    }

    public function start(): bool
    {
        return $this->client->isRunning() ? false : $this->client->start();
    }

    public function use(?Client $client)
    {
        $this->client = $client;
    }

    public static function numStats(): array
    {
        // numStats static method body hook
        return Client::numStats();
    }

    public static function debug(bool $enable = true): void
    {
        // debug static method body hook
        Client::debug($enable);
    }

    public function __get($name)
    {
        // __get non-static method body hook
        return $this->client->__get($name);
    }

    public function stats($key = null)
    {
        // stats non-static method body hook
        return $this->client->stats($key);
    }

    public function isConnected(): bool
    {
        // isConnected non-static method body hook
        return $this->client->isConnected();
    }

    public function isRunning(): bool
    {
        // isRunning non-static method body hook
        return $this->client->isRunning();
    }

    public function isStreamExist(int $streamId)
    {
        // isStreamExist non-static method body hook
        return $this->client->isStreamExist($streamId);
    }

    public function setTimeout(float $timeout): void
    {
        // setTimeout non-static method body hook
        $this->client->setTimeout($timeout);
    }

    public function openStream(string $path, $data = null, string $method = 'POST'): int
    {
        // openStream non-static method body hook
        return $this->client->openStream($path, $data, $method);
    }

    public function send(\swoole_http2_request $request): int
    {
        // send non-static method body hook
        return $this->client->send($request);
    }

    public function write(int $streamId, $data, bool $end = false): bool
    {
        // write non-static method body hook
        return $this->client->write($streamId, $data, $end);
    }

    public function recv(int $streamId, ?float $timeout = null)
    {
        // recv non-static method body hook
        return $this->client->recv($streamId, $timeout);
    }

    public function waitForAll(): bool
    {
        // waitForAll non-static method body hook
        return $this->client->waitForAll();
    }

    public function close($yield = false): bool
    {
        // close non-static method body hook
        return $this->client->close($yield);
    }

    public function closeWait($yield = 3.0): bool
    {
        // closeWait non-static method body hook
        return $this->client->closeWait($yield);
    }

    public function closeAfter(float $time): bool
    {
        // closeAfter non-static method body hook
        return $this->client->closeAfter($time);
    }

}