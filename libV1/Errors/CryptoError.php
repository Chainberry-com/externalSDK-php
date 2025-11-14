<?php

namespace OpenAPI\ClientV1\Errors;

class CryptoError extends BerrySdkError
{
    public function __construct(string $message, ?array $details = null)
    {
        parent::__construct($message, "CRYPTO_ERROR", null, $details);
        $this->name = "CryptoError";
    }
}
