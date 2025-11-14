<?php

namespace OpenAPI\ClientV1\Errors;

/**
 * Base error class for all BerrySdk errors
 */
class BerrySdkError extends \Exception
{
    public string $errorCode;
    public ?int $statusCode;
    public ?array $details;
    public string $name;

    public function __construct(string $message, string $errorCode, ?int $statusCode = null, ?array $details = null)
    {
        parent::__construct($message);
        $this->name = "BerrySdkError";
        $this->errorCode = $errorCode;
        $this->statusCode = $statusCode;
        $this->details = $details;
    }
}