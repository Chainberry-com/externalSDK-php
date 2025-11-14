<?php

namespace OpenAPI\ClientV1\Errors;

class BadRequestError extends BerrySdkError
{
    public function __construct(string $message, ?array $details = null)
    {
        parent::__construct($message, "BAD_REQUEST", 400, $details);
        $this->name = "BadRequestError";
    }
}
