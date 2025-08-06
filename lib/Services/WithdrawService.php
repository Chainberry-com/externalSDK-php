<?php

namespace OpenAPI\Client\Services;

use OpenAPI\Client\ApiSetup;
use OpenAPI\Client\Errors\BadRequestError;
use OpenAPI\Client\Errors\UnauthorizedError;
use OpenAPI\Client\Utils\CryptoUtils;
use OpenAPI\Client\Utils\Validators;

/**
 * Parameters for creating a withdrawal (without signature)
 */
class CreateWithdrawParams
{
    public string $amount;
    public string $currency;
    public ?string $paymentGatewayName = null;
    public string $callbackUrl;
    public string $tradingAccountLogin;
    public ?string $withdrawCurrency = null;
    public string $address;
}

/**
 * Withdraw service for handling withdraw operations
 */
class WithdrawService
{
    private ApiSetup $apiSetup;

    public function __construct()
    {
        $this->apiSetup = \OpenAPI\Client\BerrySdk::getApi();
    }

    /**
     * Creates a withdrawal request with a generated signature
     * 
     * @param array $params The withdrawal parameters
     * @return array A signed withdrawal request object
     * @throws \Exception
     */
    public function getSignedWithdrawRequest(array $params): array
    {
        // Validate input parameters
        $this->validateCreateWithdrawParams($params);

        $apiToken = $this->apiSetup->getConfig()->clientId;
        if (empty($apiToken)) {
            throw new \Exception("Api token is required but not found in config");
        }

        $supportedAssets = $this->apiSetup->getAssetApi()->assetControllerGetSupportedAssets();
        $supportedAssetCurrency = $this->findAssetBySymbol($supportedAssets, $params['currency']);
        
        if (empty($supportedAssetCurrency)) {
            throw new \Exception(
                "Currency {$params['currency']} is not supported. It should be one of the following BTC, ETH, USDT, USDC, BNB, LTC, TON"
            );
        }

        $withdrawCurrency = $params['withdrawCurrency'] ?? $supportedAssetCurrency['symbol'];
        
        if (!empty($params['withdrawCurrency'])) {
            $supportedAssetWithdrawCurrency = $this->findAssetBySymbol($supportedAssets, $params['withdrawCurrency']);
            if (empty($supportedAssetWithdrawCurrency)) {
                throw new \Exception(
                    "Withdraw Currency {$params['withdrawCurrency']} is not supported. It should be one of the following BTC, ETH, USDT, USDC, BNB, LTC, TON"
                );
            }
        }

        $paymentGatewayName = $params['paymentGatewayName'] ?? null;
        if (empty($paymentGatewayName)) {
            if ($this->isToken($withdrawCurrency)) {
                throw new \Exception("Payment gateway name is required!");
            }
            if ($withdrawCurrency === $params['currency']) {
                $paymentGatewayName = $supportedAssetCurrency['symbol'];
            } else {
                throw new \Exception(
                    "Withdraw currency {$withdrawCurrency} is not supported. Please specify a different withdraw currency or payment gateway."
                );
            }
        }

        // Create the payload to sign (excluding signature field)
        $payloadToSign = [
            'amount' => $params['amount'],
            'currency' => $params['currency'],
            'paymentGatewayName' => $paymentGatewayName,
            'callbackUrl' => $params['callbackUrl'],
            'apiToken' => $apiToken,
            'timestamp' => time(),
            'tradingAccountLogin' => $params['tradingAccountLogin'],
            'withdrawCurrency' => $withdrawCurrency,
            'address' => $params['address'],
        ];

        $privateKey = $this->apiSetup->getConfig()->privateKey;
        if (empty($privateKey)) {
            throw new \Exception("Private key is required but not found in config");
        }

        // Generate signature using the utils function
        return CryptoUtils::signPayload($payloadToSign, $privateKey);
    }

    /**
     * Creates and submits a withdrawal request
     * 
     * @param array $params The withdrawal parameters
     * @return \OpenAPI\Client\Model\WithdrawDto Withdrawal response
     * @throws BadRequestError
     * @throws UnauthorizedError
     * @throws \Exception
     */
    public function createWithdraw(array $params): \OpenAPI\Client\Model\WithdrawDto
    {
        $signedRequest = $this->getSignedWithdrawRequest($params);
        
        try {
            $response = $this->apiSetup->getWithdrawApi()->withdrawControllerCreateWithdraw($signedRequest);
            return $response;
        } catch (\Exception $error) {
            // Re-throw the original error - let the caller handle specific error types
            throw $error;
        }
    }

    /**
     * Gets withdrawal information with automatic JWT token authentication and retry logic
     * 
     * @param string $paymentId Payment ID
     * @param int $maxRetries Maximum retry attempts
     * @return \OpenAPI\Client\Model\GetWithdrawDto Withdrawal information
     * @throws BadRequestError
     * @throws UnauthorizedError
     * @throws \Exception
     */
    public function getWithdraw(string $paymentId, int $maxRetries = 2): \OpenAPI\Client\Model\GetWithdrawDto
    {
        $lastError = null;

        for ($attempt = 0; $attempt <= $maxRetries; $attempt++) {
            try {
                // On first attempt, try without getting a new token (use existing if available)
                if ($attempt === 0) {
                    try {
                        // Make the API call with whatever token is currently set (if any)
                        $response = $this->apiSetup->getWithdrawApi()->withdrawControllerGetWithdraw($paymentId);

                        if (!empty($response)) {
                            return $response;
                        } else {
                            throw new \Exception("No data received from API");
                        }
                    } catch (\Exception $error) {
                        // Check if it's an authentication error (401)
                        $isAuthError = $this->isAuthenticationError($error);

                        // If it's not an auth error, throw immediately
                        if (!$isAuthError) {
                            throw $error;
                        }

                        // If it's an auth error, continue to the next attempt to get a fresh token
                        $lastError = $error;
                    }
                }

                // For retry attempts or if first attempt failed with auth error, get a fresh token
                $this->apiSetup->ensureAccessToken();

                // Make the API call
                $response = $this->apiSetup->getWithdrawApi()->withdrawControllerGetWithdraw($paymentId);

                if (!empty($response)) {
                    return $response;
                } else {
                    throw new \Exception("No data received from API");
                }
            } catch (\Exception $error) {
                $lastError = $error;

                // Check if it's an authentication error (401)
                $isAuthError = $this->isAuthenticationError($error);

                // If it's not an auth error or we've exhausted retries, throw the error
                if (!$isAuthError || $attempt === $maxRetries) {
                    throw $error;
                }

                // For auth errors, wait a bit before retrying (exponential backoff)
                if ($attempt < $maxRetries) {
                    $delay = pow(2, $attempt) * 1000; // 1s, 2s, 4s...
                    usleep($delay * 1000); // Convert to microseconds
                    error_log("Authentication failed, retrying in {$delay}ms... (attempt " . ($attempt + 1) . "/" . ($maxRetries + 1) . ")");
                }
            }
        }

        // This should never be reached, but just in case
        throw $lastError ?? new \Exception("Failed to get withdrawal information after all retries");
    }

    /**
     * Gets withdrawal information with automatic JWT token authentication using environment variables
     * 
     * @param string $paymentId The payment ID to retrieve
     * @param int $maxRetries Maximum number of retries (defaults to 2)
     * @return \OpenAPI\Client\Model\GetWithdrawDto Withdrawal information
     * @throws \Exception
     */
    public function getWithdrawWithEnvAuth(string $paymentId, int $maxRetries = 2): \OpenAPI\Client\Model\GetWithdrawDto
    {
        // Check for required environment variables
        $clientId = getenv('CB_API_CLIENT_ID');
        $clientSecret = getenv('CB_API_CLIENT_SECRET');

        if (empty($clientId) || empty($clientSecret)) {
            throw new \Exception("Missing required environment variables: CB_API_CLIENT_ID and CB_API_CLIENT_SECRET");
        }

        return $this->getWithdraw($paymentId, $maxRetries);
    }

    /**
     * Validate create withdrawal parameters
     * 
     * @param array $params Parameters to validate
     * @throws \Exception
     */
    private function validateCreateWithdrawParams(array $params): void
    {
        Validators::amount()->validateAndThrow($params['amount'] ?? null, 'Amount');
        Validators::callbackUrl()->validateAndThrow($params['callbackUrl'] ?? null, 'Callback URL');
        Validators::tradingAccountLogin()->validateAndThrow($params['tradingAccountLogin'] ?? null, 'Trading Account Login');
        
        // Validate address is provided
        if (empty($params['address'])) {
            throw new \Exception("Address is required");
        }
    }

    /**
     * Find asset by symbol in supported assets
     * 
     * @param array $supportedAssets Supported assets array
     * @param string $symbol Symbol to find
     * @return array|null Found asset or null
     */
    private function findAssetBySymbol(array $supportedAssets, string $symbol): ?array
    {
        foreach ($supportedAssets as $asset) {
            if ($asset['symbol'] === $symbol) {
                // Convert SupportedAssetDto to array if it's an object
                if (is_object($asset)) {
                    return [
                        'symbol' => $asset->getSymbol(),
                        'name' => $asset->getName()
                    ];
                }
                return $asset;
            }
        }
        return null;
    }

    /**
     * Check if value is a token
     * 
     * @param string $value Value to check
     * @return bool True if token
     */
    private function isToken(string $value): bool
    {
        return strtolower($value) === 'usdt' || strtolower($value) === 'usdc';
    }

    /**
     * Check if error is an authentication error
     * 
     * @param \Exception $error Error to check
     * @return bool True if authentication error
     */
    private function isAuthenticationError(\Exception $error): bool
    {
        $message = $error->getMessage();
        
        // Check for common authentication error patterns in the message
        if (strpos($message, 'Unauthorized') !== false ||
            strpos($message, 'Invalid token') !== false ||
            strpos($message, '401') !== false) {
            return true;
        }
        
        // Use reflection to safely check for properties
        $reflection = new \ReflectionClass($error);
        
        // Check if error has status property
        if ($reflection->hasProperty('status')) {
            $statusProperty = $reflection->getProperty('status');
            $statusProperty->setAccessible(true);
            $status = $statusProperty->getValue($error);
            if ($status === 401) {
                return true;
            }
        }
        
        // Check if error has error property with statusCode
        if ($reflection->hasProperty('error')) {
            $errorProperty = $reflection->getProperty('error');
            $errorProperty->setAccessible(true);
            $errorData = $errorProperty->getValue($error);
            if (is_array($errorData) && isset($errorData['statusCode']) && $errorData['statusCode'] === 401) {
                return true;
            }
        }
        
        return false;
    }
} 