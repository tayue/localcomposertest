<?php


namespace GrpcClient;

use Google\Protobuf\Internal\Message;
use Grpc\Backoff;
use Grpc\Parser;
use Grpc\StatusCode;
use GrpcClient\Exception\GrpcClientException;
use InvalidArgumentException;


class BaseClient
{
    /**
     * @var null|GrpcClient
     */
    private $grpcClient;

    /**
     * @var array
     */
    private $options;

    /**
     * @var string
     */
    private $hostname;

    /**
     * @var bool
     */
    private $initialized = false;

    public function __construct(string $hostname, array $options = [])
    {
        $this->hostname = $hostname;
        $this->options = $options;
    }

    public function __destruct()
    {
        if ($this->grpcClient) {
            $this->grpcClient->close(true);
        }
    }

    public function __get($name)
    {
        if (! $this->initialized) {
            $this->init();
        }
        return $this->getGrpcClient()->{$name};
    }

    public function __call($name, $arguments)
    {
        if (! $this->initialized) {
            $this->init();
        }
        return $this->getGrpcClient()->{$name}(...$arguments);
    }

    public function start()
    {
        $client = $this->grpcClient;
        return $client->isRunning() || $client->start();
    }

    public function getGrpcClient(): GrpcClient
    {
        if (! $this->initialized) {
            $this->init();
        }
        return $this->grpcClient;
    }

    protected function init()
    {
        if (! empty($this->options['client'])) {
            if (! ($this->options['client'] instanceof GrpcClient)) {
                throw new InvalidArgumentException('Parameter client have to instanceof Hyperf\GrpcClient\GrpcClient');
            }
            $this->grpcClient = $this->options['client'];
        } else {
            $this->grpcClient = new GrpcClient(ChannelPool::getInstance());
            $this->grpcClient->set($this->hostname, $this->options);
        }
        if (!$this->start()) {
            $message = sprintf(
                'Grpc client start failed with error code %d when connect to %s',
                $this->grpcClient->getErrCode(),
                $this->hostname
            );
            throw new GrpcClientException($message, StatusCode::INTERNAL);
        }
        $this->initialized = true;
    }

    /**
     * Call a remote method that takes a single argument and has a
     * single output.
     *
     * @param string $method The name of the method to call
     * @param Message $argument The argument to the method
     * @param callable $deserialize A function that deserializes the response
     * @throws GrpcClientException
     * @return array|\Google\Protobuf\Internal\Message[]|\swoole_http2_response[]
     */
    protected function simpleRequest(
        string $method,
        Message $argument,
        $deserialize
    ) {
        $streamId = self::retry($this->options['retry_attempts'] ?? 3, function () use ($method, $argument) {
            echo "pre send\r\n";
            $streamId = $this->getGrpcClient()->send($this->buildRequest($method, $argument));
            echo "back send streamId:{$streamId}\r\n";
            if ($streamId === 0) {
                $this->init();
                // The client should not be used after this exception
                throw new GrpcClientException('Failed to send the request to server', StatusCode::INTERNAL);
            }
            return $streamId;
        }, $this->options['retry_interval'] ?? 100);
        echo "streamId:{$streamId}\r\n";
        return Parser::parseResponse($this->getGrpcClient()->recv($streamId), $deserialize);
    }

    /**
     * Call a remote method that takes a stream of arguments and has a single
     * output.
     *
     * @param string $method The name of the method to call
     * @param callable $deserialize A function that deserializes the response
     *
     * @return ClientStreamingCall The active call object
     */
    protected function clientStreamRequest(
        string $method,
        $deserialize
    ): ClientStreamingCall {
        $call = new ClientStreamingCall();
        $call->setClient($this->grpcClient)
            ->setMethod($method)
            ->setDeserialize($deserialize);

        return $call;
    }

    /**
     * Call a remote method with messages streaming in both directions.
     *
     * @param string $method The name of the method to call
     * @param callable $deserialize A function that deserializes the responses
     */
    protected function _bidiRequest(
        string $method,
        $deserialize
    ): BidiStreamingCall {
        $call = new BidiStreamingCall();
        $call->setClient($this->grpcClient)
            ->setMethod($method)
            ->setDeserialize($deserialize);

        return $call;
    }

    protected function buildRequest(string $method, Message $argument): Request
    {
        return new Request($method, $argument);
    }

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
}
