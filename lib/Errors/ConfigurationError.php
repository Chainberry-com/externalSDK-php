<?php

namespace OpenAPI\Client\Errors;

class ConfigurationError extends BerrySdkError
{
    public function __construct(string $message, ?array $details = null)
    {
        parent::__construct($message, "CONFIGURATION_ERROR", null, $details);
        $this->name = "ConfigurationError";
    }
}
