<?php

namespace GrpcClient;

use Google\Protobuf\Internal\Message;
use Grpc\Parser;
use Swoole\Http2\Request as BaseRequest;

class Request extends BaseRequest
{
    private const DEFAULT_CONTENT_TYPE = 'application/grpc+proto';

    public function __construct(string $method, Message $argument = null, $headers = [])
    {
        $this->method = 'POST';
        $this->headers = array_replace($this->getDefaultHeaders(), $headers);
        $this->path = $method;
        $argument && $this->data = Parser::serializeMessage($argument);
    }

    public function getDefaultHeaders(): array
    {
        return [
            'content-type' => self::DEFAULT_CONTENT_TYPE,
            'user-agent' => $this->buildDefaultUserAgent(),
        ];
    }

    private function buildDefaultUserAgent(): string
    {
        $userAgent = 'grpc-php-hyperf/1.0';
        $grpcClientVersion = "grpc@1";
        if ($grpcClientVersion) {
            $explodedVersions = explode('@', $grpcClientVersion);
            $userAgent .= ' (hyperf-grpc-client/' . $explodedVersions[0] . ')';
        }
        return $userAgent;
    }
}
