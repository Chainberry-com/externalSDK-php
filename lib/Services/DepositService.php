<?php

namespace OpenAPI\Client\Services;

use OpenAPI\Client\ApiSetup;
use OpenAPI\Client\Errors\BadRequestError;
use OpenAPI\Client\Errors\UnauthorizedError;
use OpenAPI\Client\Model\DepositRequestV2Dto;
use OpenAPI\Client\Model\DepositResponseV2Dto;
use OpenAPI\Client\Model\PaymentResponseV2Dto;
use OpenAPI\Client\Utils\CryptoUtils;
use OpenAPI\Client\Utils\Validators;

/**
 * Parameters for creating a deposit (without signature)
 */
class CreateDepositParams
{
    public string $amount;
    public string $currency;
    public string $callbackUrl;
    public string $partnerUserId;
    public ?string $network = null;
    public ?string $partnerPaymentId = null;

    /** @deprecated Use $partnerUserId instead */
    public ?string $tradingAccountLogin = null;
    /** @deprecated Use $network instead */
    public ?string $paymentGatewayName = null;
    /** @deprecated Field no longer used in V2 */
    public ?string $paymentCurrency = null;
}

/**
 * Deposit service for handling deposit operations
 */
class DepositService
{
    private ApiSetup $apiSetup;

    public function __construct()
    {
        $this->apiSetup = \OpenAPI\Client\BerrySdk::getApi();
    }

    /**
     * Creates a deposit request with a generated signature
     *
     * @param array $params The deposit parameters
     * @return DepositRequestV2Dto
     * @throws \Exception
     */
    public function getSignedDepositRequest(array $params): DepositRequestV2Dto
    {
        $this->validateCreateDepositParams($params);

        $apiToken = $this->apiSetup->getConfig()->clientId;
        if (empty($apiToken)) {
            throw new \Exception("Api token is required but not found in config");
        }

        $currency = strtoupper($params['currency']);
        $supportedAssets = $this->apiSetup->getAssetApi()->assetControllerGetSupportedAssets();
        if ($this->findAssetBySymbol($supportedAssets, $currency) === null) {
            throw new \Exception(
                "Currency {$currency} is not supported. It should be one of the supported assets returned by the Asset API."
            );
        }

        $privateKey = $this->apiSetup->getConfig()->privateKey;
        if (empty($privateKey)) {
            throw new \Exception("Private key is required but not found in config");
        }

        $partnerUserId = $this->resolvePartnerUserId($params);
        $network = $this->resolveNetwork($params);
        $partnerPaymentId = $this->resolvePartnerPaymentId($params);

        $payloadToSign = [
            'amount' => (string) $params['amount'],
            'currency' => $currency,
            'callbackUrl' => $params['callbackUrl'],
            'apiToken' => $apiToken,
            'timestamp' => time(),
            'partnerUserID' => $partnerUserId,
        ];

        if ($network !== null) {
            $payloadToSign['network'] = $network;
        }

        if ($partnerPaymentId !== null) {
            $payloadToSign['partnerPaymentID'] = $partnerPaymentId;
        }

        $signedPayload = CryptoUtils::signPayload($payloadToSign, $privateKey);

        return $this->mapToDepositRequestDto($signedPayload);
    }

    /**
     * Creates and submits a deposit request
     *
     * @param array $params The deposit parameters
     * @return DepositResponseV2Dto
     * @throws BadRequestError
     * @throws UnauthorizedError
     * @throws \Exception
     */
    public function createDeposit(array $params): DepositResponseV2Dto
    {
        $requestDto = $this->getSignedDepositRequest($params);

        try {
            return $this->apiSetup
                ->getDepositsApi()
                ->depositV2ControllerCreateDepositV2($requestDto);
        } catch (\Exception $error) {
            throw $error;
        }
    }

    /**
     * Gets deposit information
     *
     * @param string $paymentId Payment ID
     * @return PaymentResponseV2Dto Deposit information
     * @throws BadRequestError
     * @throws UnauthorizedError
     * @throws \Exception
     */
    public function getDeposit(string $paymentId, int $maxRetries = 2): PaymentResponseV2Dto
    {
        try {
            return $this->apiSetup
                ->getDepositsApi()
                ->depositV2ControllerGetDepositPaymentV2($paymentId);
        } catch (\Exception $error) {
            if ($this->isAuthenticationError($error)) {
                $this->apiSetup->ensureAccessToken();
                return $this->apiSetup
                    ->getDepositsApi()
                    ->depositV2ControllerGetDepositPaymentV2($paymentId);
            }

            throw $error;
        }
    }

    /**
     * Gets deposit information with automatic JWT token authentication using environment variables
     *
     * @param string $paymentId The payment ID to retrieve
     * @return PaymentResponseV2Dto Deposit information
     * @throws \Exception
     */
    public function getDepositWithEnvAuth(string $paymentId, int $maxRetries = 2): PaymentResponseV2Dto
    {
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
     */
    private function validateCreateDepositParams(array $params): void
    {
        Validators::amount()->validateAndThrow($params['amount'] ?? null, 'Amount');

        $currency = $params['currency'] ?? null;
        Validators::currency()->validateAndThrow($currency !== null ? strtoupper($currency) : null, 'Currency');

        Validators::callbackUrl()->validateAndThrow($params['callbackUrl'] ?? null, 'Callback URL');

        $partnerUserId = $this->resolvePartnerUserId($params);
        Validators::partnerUserId()->validateAndThrow($partnerUserId, 'Partner User ID');

        $network = $params['network'] ?? $params['paymentGatewayName'] ?? null;
        if ($network !== null && $network !== '') {
            Validators::optionalNetwork('Network')->validateAndThrow(strtoupper($network), 'Network');
        }
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

    private function mapToDepositRequestDto(array $signedPayload): DepositRequestV2Dto
    {
        $data = [
            'callback_url' => $signedPayload['callbackUrl'],
            'api_token' => $signedPayload['apiToken'],
            'timestamp' => (float) $signedPayload['timestamp'],
            'signature' => $signedPayload['signature'],
            'partner_user_id' => $signedPayload['partnerUserID'],
            'amount' => (string) $signedPayload['amount'],
            'currency' => $signedPayload['currency'],
        ];

        if (array_key_exists('partnerPaymentID', $signedPayload)) {
            $data['partner_payment_id'] = $signedPayload['partnerPaymentID'];
        }

        if (array_key_exists('network', $signedPayload)) {
            $data['network'] = $signedPayload['network'];
        }

        return new DepositRequestV2Dto($data);
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