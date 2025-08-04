<?php

namespace OpenAPI\Client\Errors;

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

/**
 * Configuration related errors
 */
class ConfigurationError extends BerrySdkError
{
    public function __construct(string $message, ?array $details = null)
    {
        parent::__construct($message, "CONFIGURATION_ERROR", null, $details);
        $this->name = "ConfigurationError";
    }
}

/**
 * Authentication related errors
 */
class AuthenticationError extends BerrySdkError
{
    public function __construct(string $message, ?int $statusCode = null, ?array $details = null)
    {
        parent::__construct($message, "AUTHENTICATION_ERROR", $statusCode, $details);
        $this->name = "AuthenticationError";
    }
}

/**
 * API request related errors
 */
class ApiError extends BerrySdkError
{
    public function __construct(string $message, ?int $statusCode = null, ?array $details = null)
    {
        parent::__construct($message, "API_ERROR", $statusCode, $details);
        $this->name = "ApiError";
    }
}

/**
 * Validation related errors
 */
class ValidationError extends BerrySdkError
{
    public function __construct(string $message, ?array $details = null)
    {
        parent::__construct($message, "VALIDATION_ERROR", null, $details);
        $this->name = "ValidationError";
    }
}

/**
 * Crypto/signature related errors
 */
class CryptoError extends BerrySdkError
{
    public function __construct(string $message, ?array $details = null)
    {
        parent::__construct($message, "CRYPTO_ERROR", null, $details);
        $this->name = "CryptoError";
    }
}

/**
 * Network/connection related errors
 */
class NetworkError extends BerrySdkError
{
    public function __construct(string $message, ?array $details = null)
    {
        parent::__construct($message, "NETWORK_ERROR", null, $details);
        $this->name = "NetworkError";
    }
}

/**
 * Rate limiting errors
 */
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

/**
 * Bad request errors
 */
class BadRequestError extends BerrySdkError
{
    public function __construct(string $message, ?array $details = null)
    {
        parent::__construct($message, "BAD_REQUEST", 400, $details);
        $this->name = "BadRequestError";
    }
}

/**
 * Unauthorized errors
 */
class UnauthorizedError extends BerrySdkError
{
    public function __construct(string $message, ?array $details = null)
    {
        parent::__construct($message, "UNAUTHORIZED", 401, $details);
        $this->name = "UnauthorizedError";
    }
} 