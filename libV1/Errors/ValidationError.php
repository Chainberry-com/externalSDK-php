<?php

namespace OpenAPI\ClientV1\Errors;

class ValidationError extends BerrySdkError
{
    public function __construct(string $message, ?array $details = null)
    {
        parent::__construct($message, "VALIDATION_ERROR", null, $details);
        $this->name = "ValidationError";
    }
}
