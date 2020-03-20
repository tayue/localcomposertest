<?php

namespace GrpcClient;

class ClientStreamingCall extends StreamingCall
{
    /**
     * @var bool
     */
    private $received = false;

    public function recv(float $timeout = GrpcClient::GRPC_DEFAULT_TIMEOUT)
    {
        if (! $this->received) {
            $this->received = true;
            return parent::recv($timeout);
        }
        trigger_error('ClientStreamingCall can only recv once!', E_USER_ERROR);
        return false;
    }
}
