<?php

namespace OpenAPI\Client\Errors;

class ApiError extends BerrySdkError
{
    public function __construct(string $message, ?int $statusCode = null, ?array $details = null)
    {
        parent::__construct($message, "API_ERROR", $statusCode, $details);
        $this->name = "ApiError";
    }
}
