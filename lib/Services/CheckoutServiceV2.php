<?php

namespace OpenAPI\Client\Services;

use OpenAPI\Client\ApiSetup;
use OpenAPI\Client\Errors\BadRequestError;
use OpenAPI\Client\Errors\UnauthorizedError;
use OpenAPI\Client\Model\CheckoutPaymentResponseV2Dto;
use OpenAPI\Client\Model\CheckoutResponseV2Dto;
use OpenAPI\Client\Model\CreateCheckoutRequestDto;
use OpenAPI\Client\Utils\CryptoUtils;
use OpenAPI\Client\Utils\Validators;

/**
 * Parameters for creating a checkout (without signature)
 */
class CreateCheckoutParams
{
    public string $amountUsd;
    public string $callbackUrl;
    public string $partnerUserId;
    public ?string $partnerPaymentId = null;
}

/**
 * Checkout service for handling checkout operations
 */
class CheckoutServiceV2
{
    private ApiSetup $apiSetup;

    public function __construct()
    {
        $this->apiSetup = \OpenAPI\Client\BerrySdk::getApi();
    }

    /**
     * Creates a checkout request with a generated signature
     *
     * @param array $params The checkout parameters
     * @return CreateCheckoutRequestDto
     * @throws \Exception
     */
    public function getSignedCheckoutRequest(array $params): CreateCheckoutRequestDto
    {
        $this->validateCreateCheckoutParams($params);

        $apiToken = $this->apiSetup->getConfig()->clientId;
        if (empty($apiToken)) {
            throw new \Exception("Api token is required but not found in config");
        }

        $privateKey = $this->apiSetup->getConfig()->privateKey;
        if (empty($privateKey)) {
            throw new \Exception("Private key is required but not found in config");
        }

        $partnerUserId = $this->resolvePartnerUserId($params);
        $partnerPaymentId = $this->resolvePartnerPaymentId($params);

        $payloadToSign = [
            'amountUsd' => (string) $params['amountUsd'],
            'callbackUrl' => $params['callbackUrl'],
            'apiToken' => $apiToken,
            'timestamp' => time(),
            'partnerUserID' => $partnerUserId,
        ];

        if ($partnerPaymentId !== null) {
            $payloadToSign['partnerPaymentID'] = $partnerPaymentId;
        }

        $signedPayload = CryptoUtils::signPayload($payloadToSign, $privateKey);

        return $this->mapToCheckoutRequestDto($signedPayload);
    }

    /**
     * Creates and submits a checkout request
     *
     * @param array $params The checkout parameters
     * @return CheckoutResponseV2Dto
     * @throws BadRequestError
     * @throws UnauthorizedError
     * @throws \Exception
     */
    public function createCheckout(array $params): CheckoutResponseV2Dto
    {
        $requestDto = $this->getSignedCheckoutRequest($params);

        try {
            return $this->apiSetup
                ->getCheckoutApi()
                ->checkoutV2ControllerCreateCheckoutV2($requestDto);
        } catch (\Exception $error) {
            throw $error;
        }
    }

    /**
     * Creates and submits update checkout request
     *
     * @param array $params The update checkout parameters
     * @return CheckoutResponseV2Dto
     * @throws BadRequestError
     * @throws UnauthorizedError
     * @throws \Exception
     */
    public function updateCheckout(array $params): CheckoutResponseV2Dto
    {
        $this->validateUpdateCheckoutParams($params);

        $network = $this->resolveNetwork($params);

        $currency = strtoupper($params['currency']);
        $supportedAssets = $this->apiSetup->getAssetApi()->assetV2ControllerGetSupportedAssetsV2();
        if ($this->findAssetBySymbol($supportedAssets, $currency) === null) {
            throw new \Exception(
                "Currency {$currency} is not supported. It should be one of the supported assets returned by the Asset API."
            );
        }

        $reqParams = [
            'checkoutID' => $params['checkoutID'],
            'currency' => $currency,
            'network' => $network,
        ];

        try {
            return $this->apiSetup
                ->getCheckoutApi()
                ->checkoutV2ControllerUpdateCheckoutV2($reqParams);
        } catch (\Exception $error) {
            throw $error;
        }
    }

    /**
     * Gets checkout information
     *
     * @param string $checkoutId Checkout ID
     * @return CheckoutResponseV2Dto Checkout information
     * @throws BadRequestError
     * @throws UnauthorizedError
     * @throws \Exception
     */
    public function getCheckout(string $checkoutId, int $maxRetries = 2): CheckoutResponseV2Dto
    {
        try {
            return $this->apiSetup
                ->getCheckoutApi()
                ->checkoutV2ControllerGetCheckoutV2($checkoutId, $maxRetries);
        } catch (\Exception $error) {
            if ($this->isAuthenticationError($error)) {
                $this->apiSetup->ensureAccessToken();
                return $this->apiSetup
                    ->getCheckoutApi()
                    ->checkoutV2ControllerGetCheckoutV2($checkoutId, $maxRetries);
            }

            throw $error;
        }
    }

    /**
     * Gets checkout payment information
     *
     * @param string $checkoutId Checkout ID
     * @return CheckoutPaymentResponseV2Dto Checkout payment information
     * @throws BadRequestError
     * @throws UnauthorizedError
     * @throws \Exception
     */
    public function getCheckoutPayment(string $checkoutId, int $maxRetries = 2): CheckoutPaymentResponseV2Dto
    {
        try {
            return $this->apiSetup
                ->getCheckoutApi()
                ->checkoutV2ControllerGetCheckoutPaymentV2($checkoutId, $maxRetries);
        } catch (\Exception $error) {
            if ($this->isAuthenticationError($error)) {
                $this->apiSetup->ensureAccessToken();
                return $this->apiSetup
                    ->getCheckoutApi()
                    ->checkoutV2ControllerGetCheckoutPaymentV2($checkoutId, $maxRetries);
            }

            throw $error;
        }
    }

    /**
     * Validate create checkout parameters
     *
     * @param array $params Parameters to validate
     */
    private function validateCreateCheckoutParams(array $params): void
    {
        Validators::amount()->validateAndThrow($params['amountUsd'] ?? null, 'Amount');

        Validators::callbackUrl()->validateAndThrow($params['callbackUrl'] ?? null, 'Callback URL');

        $partnerUserId = $this->resolvePartnerUserId($params);
        Validators::partnerUserId()->validateAndThrow($partnerUserId, 'Partner User ID');
    }

    /**
     * Validate update checkout parameters
     *
     * @param array $params Parameters to validate
     */
    private function validateUpdateCheckoutParams(array $params): void
    {
        $checkoutID = $params['checkoutID'] ?? null;
        Validators::uuid()->validateAndThrow($checkoutID !== null ? $checkoutID : null, 'Checkout Id');

        $currency = $params['currency'] ?? null;
        Validators::currency()->validateAndThrow($currency !== null ? strtoupper($currency) : null, 'Currency');

        $network = $params['network'] ?? null;
        Validators::network()->validateAndThrow(strtoupper($network), 'Network');
    }

    private function resolvePartnerUserId(array $params): string
    {
        return (string) ($params['partnerUserID']
            ?? $params['partnerUserId']
            ?? $params['tradingAccountLogin']
            ?? '');
    }

    private function resolveNetwork(array $params): ?string
    {
        $network = $params['network'] ?? $params['paymentGatewayName'] ?? null;
        return $network !== null && $network !== ''
            ? strtoupper((string) $network)
            : null;
    }

    private function resolvePartnerPaymentId(array $params): ?string
    {
        $partnerPaymentId = $params['partnerPaymentID'] ?? $params['partnerPaymentId'] ?? null;
        return $partnerPaymentId !== null && $partnerPaymentId !== ''
            ? (string) $partnerPaymentId
            : null;
    }

    private function mapToCheckoutRequestDto(array $signedPayload): CreateCheckoutRequestDto
    {
        $data = [
            'callback_url' => $signedPayload['callbackUrl'],
            'api_token' => $signedPayload['apiToken'],
            'timestamp' => (float) $signedPayload['timestamp'],
            'signature' => $signedPayload['signature'],
            'partner_user_id' => $signedPayload['partnerUserID'],
            'amount_usd' => (string) $signedPayload['amountUsd'],
        ];

        if (array_key_exists('partnerPaymentID', $signedPayload)) {
            $data['partner_payment_id'] = $signedPayload['partnerPaymentID'];
        }

        return new CreateCheckoutRequestDto($data);
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
        $needle = strtoupper($symbol);

        foreach ($supportedAssets as $asset) {
            $assetSymbol = null;

            if (is_object($asset) && method_exists($asset, 'getSymbol')) {
                $assetSymbol = strtoupper($asset->getSymbol());
            } elseif (is_array($asset) && isset($asset['symbol'])) {
                $assetSymbol = strtoupper($asset['symbol']);
            }

            if ($assetSymbol === $needle) {
                if (is_object($asset)) {
                    return [
                        'symbol' => $asset->getSymbol(),
                        'name' => method_exists($asset, 'getName') ? $asset->getName() : null,
                    ];
                }

                return $asset;
            }
        }

        return null;
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