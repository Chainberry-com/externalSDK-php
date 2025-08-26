<?php

namespace OpenAPI\Client\Errors;

class RateLimitError extends BerrySdkError
{
    public function __construct(string $message, ?int $retryAfter = null, ?array $details = null)
    {
        $details = $details ?? [];
        if ($retryAfter !== null) {
            $details['retryAfter'] = $retryAfter;
        }
        parent::__construct($message, "RATE_LIMIT_ERROR", 429, $details);
        $this->name = "RateLimitError";
    }
}
