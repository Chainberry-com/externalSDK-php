<?php

namespace OpenAPI\Client;

use OpenAPI\Client\Services\AutoConversionService;
use OpenAPI\Client\Services\DepositService;
use OpenAPI\Client\Services\WithdrawService;
use OpenAPI\Client\Utils\Logger;
use OpenAPI\Client\Utils\CryptoUtils;
use OpenAPI\Client\Errors\ConfigurationError;

/**
 * BerrySdk - PHP SDK for Chainberry API
 * 
 * This SDK provides a PHP implementation of the berrySdk functionality,
 * offering easy integration with the Chainberry API for deposits, crypto operations,
 * and other API features.
 */
class BerrySdk
{
    private static ?ApiSetup $apiSetup = null;
    private static ?Logger $logger = null;

    /**
     * Initialize the BerrySdk with configuration
     * 
     * @param array|null $config Configuration array
     * @return ApiSetup
     * @throws ConfigurationError
     * @throws NetworkError
     */
    public static function init(?array $config = null): ApiSetup
    {
        if (self::$apiSetup === null) {
            self::$apiSetup = new ApiSetup();
            self::$apiSetup->init($config);
        }
        return self::$apiSetup;
    }

    /**
     * Get the API instance
     * 
     * @return ApiSetup
     * @throws ConfigurationError
     */
    public static function getApi(): ApiSetup
    {
        if (self::$apiSetup === null) {
            throw new ConfigurationError("BerrySdk not initialized. Call BerrySdk::init() first.");
        }
        return self::$apiSetup;
    }

    /**
     * Get the logger instance
     * 
     * @return Logger
     */
    public static function getLogger(): Logger
    {
        if (self::$logger === null) {
            self::$logger = new Logger();
        }
        return self::$logger;
    }

    /**
     * Create a deposit
     * 
     * @param array $params Deposit parameters
     * @return \OpenAPI\Client\Model\DepositDto Deposit response
     * @throws BadRequestError
     * @throws UnauthorizedError
     */
    public static function createDeposit(array $params): \OpenAPI\Client\Model\DepositDto
    {
        $depositService = new DepositService();
        return $depositService->createDeposit($params);
    }

    /**
     * Create an auto conversion
     * 
     * @param array $params Auto conversion parameters
     * @return \OpenAPI\Client\Model\AutoConversionResponseDto Auto conversion response
     * @throws BadRequestError
     * @throws UnauthorizedError
     */
    public static function createAutoConversion(array $params): \OpenAPI\Client\Model\AutoConversionResponseDto
    {
        $autoConversionService = new AutoConversionService();
        return $autoConversionService->createAutoConversion($params);
    }

    /**
     * Get deposit information
     * 
     * @param string $paymentId Payment ID
     * @param int $maxRetries Maximum retry attempts
     * @return \OpenAPI\Client\Model\GetDepositDto Deposit information
     * @throws BadRequestError
     * @throws UnauthorizedError
     */
    public static function getDeposit(string $paymentId, int $maxRetries = 2): \OpenAPI\Client\Model\GetDepositDto
    {
        $depositService = new DepositService();
        return $depositService->getDeposit($paymentId, $maxRetries);
    }

    /**
     * Create a withdrawal
     * 
     * @param array $params Withdrawal parameters
     * @return \OpenAPI\Client\Model\WithdrawDto Withdrawal response
     * @throws BadRequestError
     * @throws UnauthorizedError
     */
    public static function createWithdraw(array $params): \OpenAPI\Client\Model\WithdrawDto
    {
        $withdrawService = new WithdrawService();
        return $withdrawService->createWithdraw($params);
    }

    /**
     * Get withdrawal information
     * 
     * @param string $paymentId Payment ID
     * @param int $maxRetries Maximum retry attempts
     * @return \OpenAPI\Client\Model\GetWithdrawDto Withdrawal information
     * @throws BadRequestError
     * @throws UnauthorizedError
     */
    public static function getWithdraw(string $paymentId, int $maxRetries = 2): \OpenAPI\Client\Model\GetWithdrawDto
    {
        $withdrawService = new WithdrawService();
        return $withdrawService->getWithdraw($paymentId, $maxRetries);
    }

    /**
     * Sign a payload with private key
     * 
     * @param array $payload Payload to sign
     * @param string $privateKey Private key
     * @return array Signed payload
     */
    public static function signPayload(array $payload, string $privateKey): array
    {
        return CryptoUtils::signPayload($payload, $privateKey);
    }

    /**
     * Verify a signature
     * 
     * @param array $dataWithSignature Data with signature
     * @param string $publicKey Public key
     * @return bool Verification result
     */
    public static function verifySignature(array $dataWithSignature, string $publicKey): bool
    {
        return CryptoUtils::verifySignature($dataWithSignature, $publicKey);
    }

    /**
     * Reset the SDK instance (useful for testing)
     */
    public static function reset(): void
    {
        self::$apiSetup = null;
        self::$logger = null;
    }
} 