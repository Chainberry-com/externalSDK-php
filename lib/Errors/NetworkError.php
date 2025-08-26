<?php

namespace OpenAPI\Client\Errors;

class NetworkError extends BerrySdkError
{
    public function __construct(string $message, ?array $details = null)
    {
        parent::__construct($message, "NETWORK_ERROR", null, $details);
        $this->name = "NetworkError";
    }
}
