<?php

namespace OpenAPI\Client\Services;

use OpenAPI\Client\ApiSetup;
use OpenAPI\Client\Errors\BadRequestError;
use OpenAPI\Client\Errors\UnauthorizedError;
use OpenAPI\Client\Model\AutoConversionRequestV2Dto;
use OpenAPI\Client\Model\AutoConversionResponseV2Dto;
use OpenAPI\Client\Utils\CryptoUtils;
use OpenAPI\Client\Utils\Validators;

/**
 * Parameters for creating an auto conversion (without signature)
 */
class CreateAutoConversionParams
{
    public string $fromAmount;
    public string $fromCurrency;
    public string $toCurrency;
    public string $callbackUrl;
    public string $partnerUserId;
    public ?string $fromNetwork = null;
    public ?string $toNetwork = null;
    public ?string $partnerPaymentId = null;

    /** @deprecated Use $partnerUserId instead */
    public ?string $tradingAccountLogin = null;
    /** @deprecated Use $fromNetwork/$toNetwork instead */
    public ?string $paymentGatewayName = null;
    /** @deprecated Use $fromAmount instead */
    public ?string $amount = null;
    /** @deprecated Use $fromCurrency instead */
    public ?string $currency = null;
    /** @deprecated Use $toCurrency instead */
    public ?string $paymentCurrency = null;
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
     * @return AutoConversionRequestV2Dto
     * @throws \Exception
     */
    public function getSignedAutoConversionRequest(array $params): AutoConversionRequestV2Dto
    {
        $this->validateCreateAutoConversionParams($params);

        $apiToken = $this->apiSetup->getConfig()->clientId;
        if (empty($apiToken)) {
            throw new \Exception("Api token is required but not found in config");
        }

        $fromCurrency = strtoupper($this->resolveFromCurrency($params));
        $toCurrency = strtoupper($this->resolveToCurrency($params));

        $supportedAssets = $this->apiSetup->getAssetApi()->assetControllerGetSupportedAssets();
        if ($this->findAssetBySymbol($supportedAssets, $fromCurrency) === null) {
            throw new \Exception("From currency {$fromCurrency} is not supported.");
        }
        if ($this->findAssetBySymbol($supportedAssets, $toCurrency) === null) {
            throw new \Exception("To currency {$toCurrency} is not supported.");
        }

        $privateKey = $this->apiSetup->getConfig()->privateKey;
        if (empty($privateKey)) {
            throw new \Exception("Private key is required but not found in config");
        }

        $partnerUserId = $this->resolvePartnerUserId($params);
        $fromAmount = $this->resolveFromAmount($params);
        $fromNetwork = $this->resolveFromNetwork($params);
        $toNetwork = $this->resolveToNetwork($params);
        $partnerPaymentId = $this->resolvePartnerPaymentId($params);

        $payloadToSign = [
            'fromAmount' => (string) $fromAmount,
            'fromCurrency' => $fromCurrency,
            'toCurrency' => $toCurrency,
            'toNetwork' => $toNetwork,
            'callbackUrl' => $params['callbackUrl'],
            'apiToken' => $apiToken,
            'timestamp' => time(),
            'partnerUserID' => $partnerUserId,
        ];

        if ($fromNetwork !== null) {
            $payloadToSign['fromNetwork'] = $fromNetwork;
        }

        if ($partnerPaymentId !== null) {
            $payloadToSign['partnerPaymentID'] = $partnerPaymentId;
        }

        $signedPayload = CryptoUtils::signPayload($payloadToSign, $privateKey);

        return $this->mapToAutoConversionRequestDto($signedPayload);
    }

    /**
     * Creates and submits an auto conversion request
     *
     * @param array $params The auto conversion parameters
     * @return AutoConversionResponseV2Dto Auto conversion response
     * @throws BadRequestError
     * @throws UnauthorizedError
     * @throws \Exception
     */
    public function createAutoConversion(array $params): AutoConversionResponseV2Dto
    {
        $requestDto = $this->getSignedAutoConversionRequest($params);

        try {
            return $this->apiSetup
                ->getAutoConversionApi()
                ->autoConversionV2ControllerAutoConversionV2($requestDto);
        } catch (\Exception $error) {
            throw $error;
        }
    }

    /**
     * Validate create auto conversion parameters
     *
     * @param array $params Parameters to validate
     */
    private function validateCreateAutoConversionParams(array $params): void
    {
        Validators::amount()->validateAndThrow($this->resolveFromAmount($params), 'From Amount');
        Validators::callbackUrl()->validateAndThrow($params['callbackUrl'] ?? null, 'Callback URL');

        $fromCurrency = $this->resolveFromCurrency($params);
        Validators::currency()->validateAndThrow($fromCurrency !== null ? strtoupper($fromCurrency) : null, 'From Currency');

        $toCurrency = $this->resolveToCurrency($params);
        Validators::currency()->validateAndThrow($toCurrency !== null ? strtoupper($toCurrency) : null, 'To Currency');

        $partnerUserId = $this->resolvePartnerUserId($params);
        Validators::partnerUserId()->validateAndThrow($partnerUserId, 'Partner User ID');

        $toNetwork = $this->resolveToNetwork($params);
        Validators::network('To Network')->validateAndThrow($toNetwork, 'To Network');

        $fromNetwork = $this->resolveFromNetwork($params);
        if ($fromNetwork !== null) {
            Validators::optionalNetwork('From Network')->validateAndThrow($fromNetwork, 'From Network');
        }
    }

    private function resolvePartnerUserId(array $params): string
    {
        return (string) ($params['partnerUserID']
            ?? $params['partnerUserId']
            ?? $params['tradingAccountLogin']
            ?? '');
    }

    private function resolveFromAmount(array $params): string
    {
        return (string) ($params['fromAmount']
            ?? $params['amount']
            ?? '');
    }

    private function resolveFromCurrency(array $params): string
    {
        return (string) ($params['fromCurrency']
            ?? $params['currency']
            ?? '');
    }

    private function resolveToCurrency(array $params): string
    {
        return (string) ($params['toCurrency']
            ?? $params['paymentCurrency']
            ?? '');
    }

    private function resolveFromNetwork(array $params): ?string
    {
        $network = $params['fromNetwork'] ?? null;
        if ($network === null || $network === '') {
            return null;
        }
        return strtoupper((string) $network);
    }

    private function resolveToNetwork(array $params): string
    {
        $network = $params['toNetwork'] ?? $params['paymentGatewayName'] ?? null;
        return strtoupper((string) ($network ?? ''));
    }

    private function resolvePartnerPaymentId(array $params): ?string
    {
        $partnerPaymentId = $params['partnerPaymentID'] ?? $params['partnerPaymentId'] ?? null;
        return $partnerPaymentId !== null && $partnerPaymentId !== ''
            ? (string) $partnerPaymentId
            : null;
    }

    private function mapToAutoConversionRequestDto(array $signedPayload): AutoConversionRequestV2Dto
    {
        $data = [
            'callback_url' => $signedPayload['callbackUrl'],
            'api_token' => $signedPayload['apiToken'],
            'timestamp' => (float) $signedPayload['timestamp'],
            'signature' => $signedPayload['signature'],
            'partner_user_id' => $signedPayload['partnerUserID'],
            'from_amount' => (string) $signedPayload['fromAmount'],
            'from_currency' => $signedPayload['fromCurrency'],
            'to_currency' => $signedPayload['toCurrency'],
            'to_network' => $signedPayload['toNetwork'],
        ];

        if (array_key_exists('fromNetwork', $signedPayload)) {
            $data['from_network'] = $signedPayload['fromNetwork'];
        }

        if (array_key_exists('partnerPaymentID', $signedPayload)) {
            $data['partner_payment_id'] = $signedPayload['partnerPaymentID'];
        }

        return new AutoConversionRequestV2Dto($data);
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
}
