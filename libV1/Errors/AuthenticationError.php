<?php

namespace OpenAPI\ClientV1\Errors;

class AuthenticationError extends BerrySdkError
{
    public function __construct(string $message, ?int $statusCode = null, ?array $details = null)
    {
        parent::__construct($message, "AUTHENTICATION_ERROR", $statusCode, $details);
        $this->name = "AuthenticationError";
    }
}
