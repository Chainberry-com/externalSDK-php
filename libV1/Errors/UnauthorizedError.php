<?php

namespace OpenAPI\ClientV1\Errors;

class UnauthorizedError extends BerrySdkError
{
    public function __construct(string $message, ?array $details = null)
    {
        parent::__construct($message, "UNAUTHORIZED", 401, $details);
        $this->name = "UnauthorizedError";
    }
}
