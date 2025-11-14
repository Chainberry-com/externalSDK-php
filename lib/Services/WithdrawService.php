<?php

namespace OpenAPI\Client\Services;

use OpenAPI\Client\ApiSetup;
use OpenAPI\Client\Errors\BadRequestError;
use OpenAPI\Client\Errors\UnauthorizedError;
use OpenAPI\Client\Model\WithdrawRequestV2Dto;
use OpenAPI\Client\Model\WithdrawResponseV2Dto;
use OpenAPI\Client\Model\WithdrawV2Dto;
use OpenAPI\Client\Utils\CryptoUtils;
use OpenAPI\Client\Utils\Validators;

/**
 * Parameters for creating a withdrawal (without signature)
 */
class CreateWithdrawParams
{
    public string $amount;
    public string $currency;
    public string $callbackUrl;
    public string $address;
    public string $partnerUserId;
    public ?string $network = null;
    public ?string $tag = null;
    public ?string $partnerPaymentId = null;

    /** @deprecated Use $partnerUserId instead */
    public ?string $tradingAccountLogin = null;
    /** @deprecated Use $network instead */
    public ?string $paymentGatewayName = null;
    /** @deprecated Field merged into $currency in V2 */
    public ?string $withdrawCurrency = null;
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
     * @return WithdrawRequestV2Dto
     * @throws \Exception
     */
    public function getSignedWithdrawRequest(array $params): WithdrawRequestV2Dto
    {
        $this->validateCreateWithdrawParams($params);

        $apiToken = $this->apiSetup->getConfig()->clientId;
        if (empty($apiToken)) {
            throw new \Exception("Api token is required but not found in config");
        }

        $currency = strtoupper($this->resolveWithdrawCurrency($params));
        $supportedAssets = $this->apiSetup->getAssetApi()->assetV2ControllerGetSupportedAssetsV2();
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
        $tag = $this->resolveTag($params);
        $address = $this->resolveAddress($params);

        $payloadToSign = [
            'amount' => (string) $params['amount'],
            'currency' => $currency,
            'address' => $address,
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

        if ($tag !== null) {
            $payloadToSign['tag'] = $tag;
        }

        $signedPayload = CryptoUtils::signPayload($payloadToSign, $privateKey);

        return $this->mapToWithdrawRequestDto($signedPayload);
    }

    /**
     * Creates and submits a withdrawal request
     *
     * @param array $params The withdrawal parameters
     * @return WithdrawResponseV2Dto Withdrawal response
     * @throws BadRequestError
     * @throws UnauthorizedError
     * @throws \Exception
     */
    public function createWithdraw(array $params): WithdrawResponseV2Dto
    {
        $requestDto = $this->getSignedWithdrawRequest($params);

        try {
            return $this->apiSetup
                ->getWithdrawApi()
                ->withdrawV2ControllerCreateWithdrawV2($requestDto);
        } catch (\Exception $error) {
            throw $error;
        }
    }

    /**
     * Gets withdrawal information
     *
     * @param string $paymentId Payment ID
     * @param int $maxRetries Maximum retry attempts (kept for backward compatibility)
     * @return WithdrawV2Dto Withdrawal information
     * @throws BadRequestError
     * @throws UnauthorizedError
     * @throws \Exception
     */
    public function getWithdraw(string $paymentId, int $maxRetries = 2): WithdrawV2Dto
    {
        try {
            return $this->apiSetup
                ->getWithdrawApi()
                ->withdrawV2ControllerGetWithdrawV2($paymentId);
        } catch (\Exception $error) {
            if ($this->isAuthenticationError($error)) {
                $this->apiSetup->ensureAccessToken();
                return $this->apiSetup
                    ->getWithdrawApi()
                    ->withdrawV2ControllerGetWithdrawV2($paymentId);
            }

            throw $error;
        }
    }

    /**
     * Gets withdrawal information with automatic JWT token authentication using environment variables
     *
     * @param string $paymentId The payment ID to retrieve
     * @param int $maxRetries Maximum number of retries (defaults to 2)
     * @return WithdrawV2Dto Withdrawal information
     * @throws \Exception
     */
    public function getWithdrawWithEnvAuth(string $paymentId, int $maxRetries = 2): WithdrawV2Dto
    {
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
     */
    private function validateCreateWithdrawParams(array $params): void
    {
        Validators::amount()->validateAndThrow($params['amount'] ?? null, 'Amount');

        $currency = $this->resolveWithdrawCurrency($params);
        Validators::currency()->validateAndThrow($currency !== null ? strtoupper($currency) : null, 'Currency');

        Validators::callbackUrl()->validateAndThrow($params['callbackUrl'] ?? null, 'Callback URL');

        $partnerUserId = $this->resolvePartnerUserId($params);
        Validators::partnerUserId()->validateAndThrow($partnerUserId, 'Partner User ID');

        Validators::address()->validateAndThrow($this->resolveAddress($params), 'Address');

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

    private function resolveTag(array $params): ?string
    {
        $tag = $params['tag'] ?? $params['memo'] ?? null;
        return $tag !== null && $tag !== '' ? (string) $tag : null;
    }

    private function resolveAddress(array $params): string
    {
        return (string) ($params['address'] ?? '');
    }

    private function resolveWithdrawCurrency(array $params): string
    {
        return (string) ($params['withdrawCurrency']
            ?? $params['currency']
            ?? '');
    }

    private function mapToWithdrawRequestDto(array $signedPayload): WithdrawRequestV2Dto
    {
        $data = [
            'callback_url' => $signedPayload['callbackUrl'],
            'api_token' => $signedPayload['apiToken'],
            'timestamp' => (float) $signedPayload['timestamp'],
            'signature' => $signedPayload['signature'],
            'partner_user_id' => $signedPayload['partnerUserID'],
            'amount' => (string) $signedPayload['amount'],
            'currency' => $signedPayload['currency'],
            'address' => $signedPayload['address'],
        ];

        if (array_key_exists('partnerPaymentID', $signedPayload)) {
            $data['partner_payment_id'] = $signedPayload['partnerPaymentID'];
        }

        if (array_key_exists('network', $signedPayload)) {
            $data['network'] = $signedPayload['network'];
        }

        if (array_key_exists('tag', $signedPayload)) {
            $data['tag'] = $signedPayload['tag'];
        }

        return new WithdrawRequestV2Dto($data);
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