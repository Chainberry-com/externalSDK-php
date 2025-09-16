<?php

namespace OpenAPI\Client\Services;

use OpenAPI\Client\ApiSetup;
use OpenAPI\Client\Errors\BadRequestError;
use OpenAPI\Client\Errors\UnauthorizedError;
use OpenAPI\Client\Utils\CryptoUtils;
use OpenAPI\Client\Utils\Retry;
use OpenAPI\Client\Utils\RetryOptions;
use OpenAPI\Client\Utils\Validators;

/**
 * Parameters for creating an auto conversion (without signature)
 */
class CreateAutoConversionParams
{
    public string $amount;
    public string $currency;
    public ?string $paymentGatewayName = null;
    public string $callbackUrl;
    public string $tradingAccountLogin;
    public string $paymentCurrency;
}

/**
 * Auto conversion service for handling auto conversion operations
 */
class AutoConversionService
{
    private ApiSetup $apiSetup;

    public function __construct()
    {
        $this->apiSetup = \OpenAPI\Client\BerrySdk::getApi();
    }

    /**
     * Creates an auto conversion request with a generated signature
     * 
     * @param array $params The auto conversion parameters
     * @return array A signed auto conversion request object
     * @throws \Exception
     */
    public function getSignedAutoConversionRequest(array $params): array
    {
        // Validate input parameters
        $this->validateCreateAutoConversionParams($params);

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

        $paymentCurrency = $params['paymentCurrency'];
        
        $supportedAssetPaymentCurrency = $this->findAssetBySymbol($supportedAssets, $paymentCurrency);
        if (empty($supportedAssetPaymentCurrency)) {
            throw new \Exception(
                "Payment Currency {$paymentCurrency} is not supported. It should be one of the following BTC, ETH, USDT, USDC, BNB, LTC, TON"
            );
        }

        $paymentGatewayName = $params['paymentGatewayName'] ?? null;
        if (empty($paymentGatewayName)) {
            if ($this->isToken($paymentCurrency)) {
                throw new \Exception("Payment gateway name is required!");
            }
            if ($paymentCurrency === $params['currency']) {
                $paymentGatewayName = $supportedAssetCurrency['symbol'];
            } else {
                throw new \Exception(
                    "Payment currency {$paymentCurrency} is not supported. Please specify a different payment currency or payment gateway."
                );
            }
        }

        // Create the payload to sign (excluding signature field)
        $payloadToSign = [
            'amount' => $params['amount'],
            'currency' => $params['currency'],
            'paymentGatewayName' => $paymentGatewayName,
            'paymentCurrency' => $paymentCurrency,
            'callbackUrl' => $params['callbackUrl'],
            'apiToken' => $apiToken,
            'timestamp' => time(),
            'tradingAccountLogin' => $params['tradingAccountLogin'],
        ];

        $privateKey = $this->apiSetup->getConfig()->privateKey;
        if (empty($privateKey)) {
            throw new \Exception("Private key is required but not found in config");
        }

        // Generate signature using the utils function
        return CryptoUtils::signPayload($payloadToSign, $privateKey);
    }

    /**
     * Creates and submits an auto conversion request
     * 
     * @param array $params The auto conversion parameters
     * @return \OpenAPI\Client\Model\AutoConversionResponseDto Auto conversion response
     * @throws BadRequestError
     * @throws UnauthorizedError
     * @throws \Exception
     */
    public function createAutoConversion(array $params): \OpenAPI\Client\Model\AutoConversionResponseDto
    {
        $signedRequest = $this->getSignedAutoConversionRequest($params);
        
        try {
            $response = $this->apiSetup->getAutoConversionApi()->autoConversionControllerAutoConversion($signedRequest);
            return $response;
        } catch (\Exception $error) {
            // Re-throw the original error - let the caller handle specific error types
            throw $error;
        }
    }

    /**
     * Validate create auto conversion parameters
     * 
     * @param array $params Parameters to validate
     * @throws \Exception
     */
    private function validateCreateAutoConversionParams(array $params): void
    {
        Validators::amount()->validateAndThrow($params['amount'] ?? null, 'Amount');
        Validators::callbackUrl()->validateAndThrow($params['callbackUrl'] ?? null, 'Callback URL');
        Validators::tradingAccountLogin()->validateAndThrow($params['tradingAccountLogin'] ?? null, 'Trading Account Login');
        
        if (empty($params['paymentCurrency'])) {
            throw new \Exception("Payment currency is required");
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
}
