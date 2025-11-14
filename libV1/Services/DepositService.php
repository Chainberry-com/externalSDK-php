<?php

namespace OpenAPI\ClientV1\Services;

use OpenAPI\ClientV1\ApiSetup;
use OpenAPI\ClientV1\Errors\BadRequestError;
use OpenAPI\ClientV1\Errors\UnauthorizedError;
use OpenAPI\ClientV1\Utils\CryptoUtils;
use OpenAPI\ClientV1\Utils\Retry;
use OpenAPI\ClientV1\Utils\RetryOptions;
use OpenAPI\ClientV1\Utils\Validators;

/**
 * Parameters for creating a deposit (without signature)
 */
class CreateDepositParams
{
    public string $amount;
    public string $currency;
    public ?string $paymentGatewayName = null;
    public ?string $paymentCurrency = null;
    public string $callbackUrl;
    public string $tradingAccountLogin;
}

/**
 * Deposit service for handling deposit operations
 */
class DepositService
{
    private ApiSetup $apiSetup;

    public function __construct()
    {
        $this->apiSetup = \OpenAPI\ClientV1\BerrySdk::getApi();
    }

    /**
     * Creates a deposit request with a generated signature
     * 
     * @param array $params The deposit parameters
     * @return array A signed deposit request object
     * @throws \Exception
     */
    public function getSignedDepositRequest(array $params): array
    {
        // Validate input parameters
        $this->validateCreateDepositParams($params);

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

        $paymentCurrency = $params['paymentCurrency'] ?? $supportedAssetCurrency['symbol'];
        
        if (!empty($params['paymentCurrency'])) {
            $supportedAssetPaymentCurrency = $this->findAssetBySymbol($supportedAssets, $params['paymentCurrency']);
            if (empty($supportedAssetPaymentCurrency)) {
                throw new \Exception(
                    "Payment Currency {$params['paymentCurrency']} is not supported. It should be one of the following BTC, ETH, USDT, USDC, BNB, LTC, TON"
                );
            }
        }

        $paymentGatewayName = $params['paymentGatewayName'] ?? null;
        // if (empty($paymentGatewayName)) {
        //     if ($this->isToken($paymentCurrency)) {
        //         throw new \Exception("Payment gateway name is required!");
        //     }
        //     if ($paymentCurrency === $params['currency']) {
        //         $paymentGatewayName = $supportedAssetCurrency['symbol'];
        //     } else {
        //         throw new \Exception(
        //             "Payment currency {$paymentCurrency} is not supported. Please specify a different payment currency or payment gateway."
        //         );
        //     }
        // }

        // Create the payload to sign (excluding signature field)
        $payloadToSign = [
            'amount' => $params['amount'],
            'currency' => $params['currency'],
            // 'paymentGatewayName' => $paymentGatewayName,
            'paymentCurrency' => $paymentCurrency,
            'callbackUrl' => $params['callbackUrl'],
            'apiToken' => $apiToken,
            'timestamp' => time(),
            'tradingAccountLogin' => $params['tradingAccountLogin'],
        ];

        $excludeKeys = ['signature', 'paymentGatewayName'];

        $additionalParams = array_diff_key(
            $params,
            $payloadToSign,
            array_flip($excludeKeys)
        );
        $payloadToSign = array_merge($payloadToSign, $additionalParams);

        if ($paymentGatewayName) {
            $payloadToSign['paymentGatewayName'] = $paymentGatewayName;
        }

        $privateKey = $this->apiSetup->getConfig()->privateKey;
        if (empty($privateKey)) {
            throw new \Exception("Private key is required but not found in config");
        }

        // Generate signature using the utils function
        return CryptoUtils::signPayload($payloadToSign, $privateKey);
    }

    /**
     * Creates and submits a deposit request
     * 
     * @param array $params The deposit parameters
     * @return \OpenAPI\ClientV1\Model\DepositDto Deposit response
     * @throws BadRequestError
     * @throws UnauthorizedError
     * @throws \Exception
     */
    public function createDeposit(array $params): \OpenAPI\ClientV1\Model\DepositDto
    {
        $signedRequest = $this->getSignedDepositRequest($params);
        
        try {
            $response = $this->apiSetup->getDepositsApi()->depositControllerCreateDeposit($signedRequest);
            return $response;
        } catch (\Exception $error) {
            // Re-throw the original error - let the caller handle specific error types
            throw $error;
        }
    }

    /**
     * Gets deposit information with automatic JWT token authentication and retry logic
     * 
     * @param string $paymentId Payment ID
     * @param int $maxRetries Maximum retry attempts
     * @return \OpenAPI\ClientV1\Model\GetDepositPaymentDto Deposit information
     * @throws BadRequestError
     * @throws UnauthorizedError
     * @throws \Exception
     */
    public function getDeposit(string $paymentId, int $maxRetries = 2): \OpenAPI\ClientV1\Model\GetDepositPaymentDto
    {
        $lastError = null;

        for ($attempt = 0; $attempt <= $maxRetries; $attempt++) {
            try {
                // On first attempt, try without getting a new token (use existing if available)
                if ($attempt === 0) {
                    try {
                        // Make the API call with whatever token is currently set (if any)
                        $response = $this->apiSetup->getDepositsApi()->depositControllerGetDepositPayment($paymentId);

                        if (!empty($response)) {
                            return is_array($response) ? $response[0] : $response;
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
                $response = $this->apiSetup->getDepositsApi()->depositControllerGetDepositPayment($paymentId);

                if (!empty($response)) {
                    return is_array($response) ? $response[0] : $response;
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
        throw $lastError ?? new \Exception("Failed to get deposit information after all retries");
    }

    /**
     * Gets deposit information with automatic JWT token authentication using environment variables
     * 
     * @param string $paymentId The payment ID to retrieve
     * @param int $maxRetries Maximum number of retries (defaults to 2)
     * @return \OpenAPI\ClientV1\Model\GetDepositPaymentDto Deposit information
     * @throws \Exception
     */
    public function getDepositWithEnvAuth(string $paymentId, int $maxRetries = 2): \OpenAPI\ClientV1\Model\GetDepositPaymentDto
    {
        // Check for required environment variables
        $clientId = getenv('CB_API_CLIENT_ID');
        $clientSecret = getenv('CB_API_CLIENT_SECRET');

        if (empty($clientId) || empty($clientSecret)) {
            throw new \Exception("Missing required environment variables: CB_API_CLIENT_ID and CB_API_CLIENT_SECRET");
        }

        return $this->getDeposit($paymentId, $maxRetries);
    }

    /**
     * Validate create deposit parameters
     * 
     * @param array $params Parameters to validate
     * @throws \Exception
     */
    private function validateCreateDepositParams(array $params): void
    {
        Validators::amount()->validateAndThrow($params['amount'] ?? null, 'Amount');
        // Validators::currency()->validateAndThrow($params['currency'] ?? null, 'Currency');
        Validators::callbackUrl()->validateAndThrow($params['callbackUrl'] ?? null, 'Callback URL');
        Validators::tradingAccountLogin()->validateAndThrow($params['tradingAccountLogin'] ?? null, 'Trading Account Login');
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