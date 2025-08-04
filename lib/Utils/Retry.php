<?php

namespace OpenAPI\Client\Utils;

/**
 * Retry options
 */
class RetryOptions
{
    public ?int $maxAttempts = null;
    public ?int $baseDelay = null;
    public ?int $maxDelay = null;
    public ?float $backoffMultiplier = null;
    public $retryCondition = null;
    public $onRetry = null;

    public function __construct(array $options = [])
    {
        $this->maxAttempts = $options['maxAttempts'] ?? 3;
        $this->baseDelay = $options['baseDelay'] ?? 1000;
        $this->maxDelay = $options['maxDelay'] ?? 10000;
        $this->backoffMultiplier = $options['backoffMultiplier'] ?? 2.0;
        $this->retryCondition = $options['retryCondition'] ?? null;
        $this->onRetry = $options['onRetry'] ?? null;
    }
}

/**
 * Retry error
 */
class RetryError extends \Exception
{
    public int $attempts;
    public $lastError;

    public function __construct(string $message, int $attempts, $lastError)
    {
        parent::__construct($message);
        $this->name = "RetryError";
        $this->attempts = $attempts;
        $this->lastError = $lastError;
    }
}

/**
 * Retry utility class
 */
class Retry
{
    /**
     * Default retry condition
     * 
     * @param mixed $error Error to check
     * @return bool Whether to retry
     */
    private static function defaultRetryCondition($error): bool
    {
        // Default retry condition: retry on network errors or 5xx status codes
        return (
            (is_object($error) && property_exists($error, 'code') && $error->code === 'NETWORK_ERROR') ||
            (is_object($error) && property_exists($error, 'statusCode') && $error->statusCode >= 500) ||
            (is_object($error) && property_exists($error, 'status') && $error->status >= 500) ||
            (is_string($error) && (strpos($error, 'timeout') !== false || strpos($error, 'network') !== false))
        );
    }

    /**
     * Default on retry callback
     * 
     * @param int $attempt Attempt number
     * @param mixed $error Error
     * @param int $delay Delay in milliseconds
     */
    private static function defaultOnRetry(int $attempt, $error, int $delay): void
    {
        error_log("Retry attempt {$attempt} after {$delay}ms due to: " . (is_string($error) ? $error : $error->getMessage()));
    }

    /**
     * Execute operation with retry logic
     * 
     * @param callable $operation Operation to retry
     * @param RetryOptions|null $options Retry options
     * @return mixed Operation result
     * @throws RetryError
     */
    public static function withRetry(callable $operation, ?RetryOptions $options = null)
    {
        $options = $options ?? new RetryOptions();
        
        $maxAttempts = $options->maxAttempts ?? 3;
        $baseDelay = $options->baseDelay ?? 1000;
        $maxDelay = $options->maxDelay ?? 10000;
        $backoffMultiplier = $options->backoffMultiplier ?? 2.0;
        $retryCondition = $options->retryCondition ?? [self::class, 'defaultRetryCondition'];
        $onRetry = $options->onRetry ?? [self::class, 'defaultOnRetry'];

        $lastError = null;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                return $operation();
            } catch (\Exception $error) {
                $lastError = $error;

                // Don't retry if we've reached max attempts or if the error shouldn't be retried
                if ($attempt === $maxAttempts || !$retryCondition($error)) {
                    throw $error;
                }

                // Calculate delay with exponential backoff
                $delay = min($baseDelay * pow($backoffMultiplier, $attempt - 1), $maxDelay);

                // Call onRetry callback
                $onRetry($attempt, $error, $delay);

                // Wait before retrying
                usleep($delay * 1000); // Convert milliseconds to microseconds
            }
        }

        // This should never be reached, but just in case
        throw new RetryError("Operation failed after {$maxAttempts} attempts", $maxAttempts, $lastError);
    }

    /**
     * Create a retryable API call
     * 
     * @param callable $apiCall API call to make retryable
     * @param RetryOptions|null $options Retry options
     * @return callable Retryable API call
     */
    public static function createRetryableApiCall(callable $apiCall, ?RetryOptions $options = null): callable
    {
        return function() use ($apiCall, $options) {
            return self::withRetry($apiCall, $options);
        };
    }
} 