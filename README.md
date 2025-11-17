# Chainberry SDK

For full details on how to use the ChainBerry SDK, check out the official documentation here: https://chainberry-docs.notaku.site.
Please note that the docs are still a work in progress, and we're actively updating them - so feel free to reach out at support@chianberry.com if something's missing or unclear!

## Overview

This PHP SDK provides easy integration with the Chainberry API for deposits, crypto operations, and other API features. It includes both low-level API access and a high-level BerrySdk wrapper for simplified usage.

## Features

- **API Setup & Configuration**: Easy initialization with environment variables or configuration arrays
- **Deposit Management**: Create and retrieve deposits with automatic signature generation
- **Crypto Operations**: Sign payloads and verify signatures using RSA-SHA256
- **Dual API Coverage**: Target both legacy V1 and modern V2 endpoints from one SDK
- **Error Handling**: Comprehensive error classes for different types of errors
- **Retry Logic**: Built-in retry mechanisms with exponential backoff
- **Validation**: Input validation with customizable rules
- **Logging**: Configurable logging system
- **OAuth Authentication**: Automatic OAuth token management

## Installation & Usage

### Requirements

PHP 8.1 and later.

### Composer

To install the bindings via [Composer](https://getcomposer.org/), add the following to `composer.json`:

```json
{
  "minimum-stability": "dev",
  "prefer-stable": true,
  "require": {
    "chainberry/external-sdk-php": "dev-main"
  }
}
```

Then run `composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
<?php
require_once('/path/to/OpenAPIClient-php/vendor/autoload.php');
```

## Quick Start with BerrySdk

### Configuration

#### Environment Variables

Set the following environment variables:

```bash
export CB_API_ENVIRONMENT=staging  # or production, local
export CB_API_VERSION=v2  # or v1 (defaults to v1 if not set)
export CB_API_CLIENT_ID=your_client_id
export CB_API_CLIENT_SECRET=your_client_secret
export CB_API_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----"
```

#### Configuration Array

Alternatively, you can pass configuration directly:

```php
$config = [
    'environment' => 'staging', // or 'production', 'local'
    'apiVersion' => 'v2', // or 'v1' (defaults to 'v1' if not set)
    'clientId' => 'your_client_id',
    'clientSecret' => 'your_client_secret',
    'privateKey' => '-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----'
];
```

### Environment and API Version Matrix (V1 vs V2)

The SDK uses two separate configuration values:
- **Environment** (`CB_API_ENVIRONMENT` or `$config['environment']`): The deployment environment (staging, production, or local)
- **API Version** (`CB_API_VERSION` or `$config['apiVersion']`): The API version to use (v1 or v2)

Both values are combined to determine the base URL. The API version defaults to `v1` if not specified.

| Environment   | Constant                      | API Version | Base URL                                    | Typical use case                 |
| ------------- | ----------------------------- | ----------- | ------------------------------------------- | -------------------------------- |
| `staging`     | `Environment::STAGING`        | `v1`        | `https://api-stg.chainberry.com/api/v1`     | Legacy flows in staging          |
| `staging`     | `Environment::STAGING`        | `v2`        | `https://api-stg.chainberry.com/api/v2`     | New V2 flows in staging          |
| `production`  | `Environment::PRODUCTION`     | `v1`        | `https://api.chainberry.com/api/v1`         | Legacy flows in production       |
| `production`  | `Environment::PRODUCTION`     | `v2`        | `https://api.chainberry.com/api/v2`         | New V2 flows in production       |
| `local`       | `Environment::LOCAL`          | `v1`        | `http://192.168.0.226:3001/api/v1`          | Local V1 gateway                 |
| `local`       | `Environment::LOCAL`          | `v2`        | `http://192.168.0.226:3001/api/v2`          | Local V2 gateway                 |

> You can call both V1 and V2 helpers from the same process, but make sure the `CB_API_VERSION` (or `$config['apiVersion']`) matches the endpoints you want to invoke. Mixing V1 helpers with a V2 API version (or vice versa) will result in authentication failures.

### Initialize the SDK (shared bootstrap)

```php
<?php

require_once 'vendor/autoload.php';

use OpenAPI\Client\BerrySdk;

// Initialize with environment variables
BerrySdk::init();

// Or initialize with configuration array
BerrySdk::init($config);
```

### V2 Quick Start Examples

Make sure `CB_API_VERSION` (or `$config['apiVersion']`) is set to `v2` before calling the helpers in this section.

```php
<?php

use OpenAPI\Client\BerrySdk;

BerrySdk::init();

// Create a deposit (V2)
$deposit = BerrySdk::createDepositV2([
    'amount' => '100.00',
    'currency' => 'USDC',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'network' => 'ETH',
]);

// Create a withdrawal (V2)
$withdraw = BerrySdk::createWithdrawV2([
    'amount' => '50.00',
    'currency' => 'USDT',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'address' => '0x742d35Cc6634C0532925a3b8D4C9db96C4b4d8b6',
    'network' => 'TRX',
]);

// Fetch existing records (V2)
$depositInfo = BerrySdk::getDepositV2('payment_id');
$withdrawInfo = BerrySdk::getWithdrawV2('payment_id');
```

### V1 Quick Start Examples (Legacy)

If you still rely on the original contracts, set `CB_API_VERSION` to `v1` (or omit it, as `v1` is the default).

```php
<?php

use OpenAPI\Client\BerrySdk;

BerrySdk::init();

// Create a deposit (V1)
$deposit = BerrySdk::createDeposit([
    'amount' => '100.00',
    'currency' => 'USDT',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'tradingAccountLogin' => 'user123',
    'address' => '0x742d35Cc6634C0532925a3b8D4C9db96C4b4d8b6',
]);

// Create a withdrawal (V1)
$withdraw = BerrySdk::createWithdraw([
    'amount' => '50.00',
    'currency' => 'USDT',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'tradingAccountLogin' => 'user123',
    'address' => '0x742d35Cc6634C0532925a3b8D4C9db96C4b4d8b6',
]);

// Fetch existing records (V1)
$depositInfo = BerrySdk::getDeposit('payment_id');
$withdrawInfo = BerrySdk::getWithdraw('payment_id');
```

### Crypto Operations

```php
<?php

use OpenAPI\Client\BerrySdk;

// Initialize the SDK
BerrySdk::init();

// Sign a payload
$payload = [
    'amount' => '100.00',
    'currency' => 'USDT',
    'timestamp' => time()
];

$privateKey = '-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----';
$signedPayload = BerrySdk::signPayload($payload, $privateKey);

// Verify a signature
$publicKey = '-----BEGIN PUBLIC KEY-----\n...\n-----END PUBLIC KEY-----';
$isValid = BerrySdk::verifySignature($signedPayload, $publicKey);

echo "Signature valid: " . ($isValid ? 'Yes' : 'No');
```

## Advanced Usage

### Using the API Setup Directly

```php
<?php

use OpenAPI\Client\ApiSetup;
use OpenAPI\Client\Environment;

// Get API setup instance
$apiSetup = ApiSetup::getInstance();

// Initialize with configuration
$apiSetup->init([
    'environment' => Environment::STAGING,
    'apiVersion' => 'v2', // or 'v1'
    'clientId' => 'your_client_id',
    'clientSecret' => 'your_client_secret',
    'privateKey' => 'your_private_key'
]);

// Get API instances
$assetApi = $apiSetup->getAssetApi();
$depositsApi = $apiSetup->getDepositsApi();
$oauthApi = $apiSetup->getOauthApi();

// Use APIs directly
$supportedAssets = $assetApi->assetV2ControllerGetSupportedAssetsV2();
```

### Using the Deposit Service

```php
<?php

use OpenAPI\Client\Services\DepositService;

// Initialize the SDK first
BerrySdk::init();

// Create deposit service
$depositService = new DepositService();

// Get signed deposit request
$signedRequest = $depositService->getSignedDepositRequest([
    'amount' => '100.00',
    'currency' => 'USDT',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'network' => 'ETH'
]);

// Create deposit
$deposit = $depositService->createDeposit([
    'amount' => '100.00',
    'currency' => 'USDT',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'network' => 'ETH'
]);

// Get deposit with retry logic
$depositInfo = $depositService->getDeposit('payment_id', 3); // 3 retries
```

### Using the Withdraw Service

```php
<?php

use OpenAPI\Client\Services\WithdrawService;

// Initialize the SDK first
BerrySdk::init();

// Create withdraw service
$withdrawService = new WithdrawService();

// Get signed withdrawal request
$signedRequest = $withdrawService->getSignedWithdrawRequest([
    'amount' => '50.00',
    'currency' => 'USDT',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'address' => '0x742d35Cc6634C0532925a3b8D4C9db96C4b4d8b6',
    'network' => 'TRX'
]);

// Create withdrawal
$withdraw = $withdrawService->createWithdraw([
    'amount' => '50.00',
    'currency' => 'USDT',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'address' => '0x742d35Cc6634C0532925a3b8D4C9db96C4b4d8b6',
    'network' => 'TRX'
]);

// Get withdrawal with retry logic
$withdrawInfo = $withdrawService->getWithdraw('payment_id', 3); // 3 retries
```

### Error Handling

```php
<?php

use OpenAPI\Client\BerrySdk;
use OpenAPI\Client\Errors\BadRequestError;
use OpenAPI\Client\Errors\UnauthorizedError;
use OpenAPI\Client\Errors\ConfigurationError;
use OpenAPI\Client\Errors\NetworkError;

try {
    BerrySdk::init();
    $deposit = BerrySdk::createDeposit($params);
} catch (ConfigurationError $e) {
    echo "Configuration error: " . $e->getMessage();
} catch (BadRequestError $e) {
    echo "Bad request: " . $e->getMessage();
} catch (UnauthorizedError $e) {
    echo "Unauthorized: " . $e->getMessage();
} catch (NetworkError $e) {
    echo "Network error: " . $e->getMessage();
} catch (Exception $e) {
    echo "Unexpected error: " . $e->getMessage();
}
```

### Validation

```php
<?php

use OpenAPI\Client\Utils\Validators;
use OpenAPI\Client\Utils\ValidationRules;

// Use pre-built validators
$emailValidator = Validators::email();
$result = $emailValidator->validate('test@example.com');
if (!$result->isValid) {
    echo "Validation errors: " . implode(', ', $result->errors);
}

// Create custom validator
$customValidator = new Validator();
$customValidator
    ->addRule(ValidationRules::required("Field is required"))
    ->addRule(ValidationRules::minLength(5, "Minimum 5 characters"))
    ->addRule(ValidationRules::pattern('/^[A-Z]+$/', "Only uppercase letters"));

$result = $customValidator->validate('HELLO');
if (!$result->isValid) {
    echo "Validation errors: " . implode(', ', $result->errors);
}
```

### Retry Logic

```php
<?php

use OpenAPI\Client\Utils\Retry;
use OpenAPI\Client\Utils\RetryOptions;

// Use retry with default options
$result = Retry::withRetry(function() {
    // Your operation here
    return someApiCall();
});

// Use retry with custom options
$options = new RetryOptions([
    'maxAttempts' => 5,
    'baseDelay' => 2000,
    'maxDelay' => 30000,
    'backoffMultiplier' => 1.5
]);

$result = Retry::withRetry(function() {
    return someApiCall();
}, $options);
```

### Logging

```php
<?php

use OpenAPI\Client\BerrySdk;
use OpenAPI\Client\Utils\Logger;
use OpenAPI\Client\Utils\LogLevel;

// Get logger instance
$logger = BerrySdk::getLogger();

// Configure logger
$logger->setLevel(LogLevel::DEBUG);
$logger->setPrefix('[MyApp]');

// Log messages
$logger->info('Application started');
$logger->debug('Debug information', ['data' => $someData]);
$logger->warn('Warning message');
$logger->error('Error occurred', ['error' => $error]);
```

## Low-Level API Usage

For direct API access without the BerrySdk wrapper, you can use the generated API classes directly:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');




$apiInstance = new OpenAPI\Client\Api\AssetApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->assetV2ControllerGetSupportedAssetsV2();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AssetApi->assetV2ControllerGetSupportedAssetsV2: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to _http://localhost:3001/api/v1_

| Class               | Method                                                                                                                         | HTTP request                              | Description                                                                                           |
| ------------------- | ------------------------------------------------------------------------------------------------------------------------------ | ----------------------------------------- | ----------------------------------------------------------------------------------------------------- |
| _AssetApi_          | [**assetV2ControllerGetSupportedAssetsV2**](docs/Api/AssetApi.md#assetv2controllergetsupportedassetsv2)                        | **GET** /asset                            |
| _ConvertApi_        | [**autoConversionV2ControllerAutoConversionV2**](docs/Api/ConvertApi.md#autoconversionv2controllerautoconversionv2)             | **POST** /convert                         |
| _BinanceApi_        | [**binanceControllerExecuteTradingWorkflow**](docs/Api/BinanceApi.md#binancecontrollerexecutetradingworkflow)                  | **POST** /binance/workflow/execute        |
| _BinanceApi_        | [**binanceControllerGetAveragePrice**](docs/Api/BinanceApi.md#binancecontrollergetaverageprice)                                | **GET** /binance/average-price/{symbol}   |
| _BinanceApi_        | [**binanceControllerGetAveragePrices**](docs/Api/BinanceApi.md#binancecontrollergetaverageprices)                              | **GET** /binance/average-prices           |
| _BinanceApi_        | [**binanceControllerGetCommonAveragePrices**](docs/Api/BinanceApi.md#binancecontrollergetcommonaverageprices)                  | **GET** /binance/common-average-prices    |
| _BinanceApi_        | [**binanceControllerGetWithdrawFee**](docs/Api/BinanceApi.md#binancecontrollergetwithdrawfee)                                  | **GET** /binance/withdraw-fee/{currency}  |
| _BinanceApi_        | [**binanceControllerValidateSymbol**](docs/Api/BinanceApi.md#binancecontrollervalidatesymbol)                                  | **GET** /binance/validate-symbol/{symbol} |
| _BinanceApi_        | [**binanceControllerWithdrawToFireblocks**](docs/Api/BinanceApi.md#binancecontrollerwithdrawtofireblocks)                      | **POST** /binance/withdraw                |
| _DepositsApi_       | [**depositV2ControllerCreateDepositV2**](docs/Api/DepositsApi.md#depositv2controllercreatedepositv2)                           | **POST** /deposits                        |
| _DepositsApi_       | [**depositV2ControllerGetDepositV2**](docs/Api/DepositsApi.md#depositv2controllergetdepositv2)                                 | **GET** /deposits/{paymentId}             |
| _DepositsApi_       | [**depositV2ControllerGetDepositPaymentV2**](docs/Api/DepositsApi.md#depositv2controllergetdepositpaymentv2)                   | **GET** /deposits/payments/{paymentId}    |
| _DepositsApi_       | [**depositControllerSetupSupportingAssets**](docs/Api/DepositsApi.md#depositcontrollersetupsupportingassets)                   | **GET** /deposits/setup-supporting-assets |
| _DepositsApi_       | [**depositControllerSyncUpWithFireblocks**](docs/Api/DepositsApi.md#depositcontrollersyncupwithfireblocks)                     | **GET** /deposits/sync-up-with-fireblocks |
| _HealthApi_         | [**appControllerHealth**](docs/Api/HealthApi.md#appcontrollerhealth)                                                           | **GET** /health                           | Get the health of the API                                                                             |
| _KytApi_            | [**kytControllerHandleMonitorNotification**](docs/Api/KytApi.md#kytcontrollerhandlemonitornotification)                        | **POST** /kyt                             | Process KYT monitor notification                                                                      |
| _OauthApi_          | [**oAuthV2ControllerTokenV2**](docs/Api/OauthApi.md#oauthv2controllertokenv2)                                                  | **POST** /oauth/token                     |
| _PartnersApi_       | [**partnerControllerGetCurrentPartner**](docs/Api/PartnersApi.md#partnercontrollergetcurrentpartner)                           | **GET** /partners/me                      |
| _PublicKeyApi_      | [**publicKeyControllerDownloadPublicKey**](docs/Api/PublicKeyApi.md#publickeycontrollerdownloadpublickey)                      | **GET** /public-key                       |
| _SideshiftApi_      | [**sideShiftControllerCreateVariableShift**](docs/Api/SideshiftApi.md#sideshiftcontrollercreatevariableshift)                  | **POST** /sideshift/shifts/variable       |
| _SideshiftApi_      | [**sideShiftControllerGetPair**](docs/Api/SideshiftApi.md#sideshiftcontrollergetpair)                                          | **GET** /sideshift/pair/{from}/{to}       |
| _SideshiftApi_      | [**sideShiftControllerGetShift**](docs/Api/SideshiftApi.md#sideshiftcontrollergetshift)                                        | **GET** /sideshift/shifts/{shiftId}       |
| _TestApi_           | [**testV2ControllerInitParamsV2**](docs/Api/TestApi.md#testv2controllerinitparamsv2)                                           | **POST** /test/init-params                | Test the init params with JWT token and apiToken                                                      |
| _TestApi_           | [**testControllerTestCallback**](docs/Api/TestApi.md#testcontrollertestcallback)                                               | **POST** /test/callback                   |
| _TestApi_           | [**testControllerTestJwt**](docs/Api/TestApi.md#testcontrollertestjwt)                                                         | **GET** /test/jwt                         | Test the API with JWT token                                                                           |
| _TestApi_           | [**testControllerTestSignatureGeneration**](docs/Api/TestApi.md#testcontrollertestsignaturegeneration)                         | **POST** /test/signature-generation       | Test the API with signature generation                                                                |
| _TestApi_           | [**testControllerTestSignatureVerificationMiddleware**](docs/Api/TestApi.md#testcontrollertestsignatureverificationmiddleware) | **POST** /test/signature-verification     | Test the API with signature verification middleware. Requires apiToken in body. ApiToken is partnerId |
| _WebhookApi_        | [**webhookControllerHandleWebhook**](docs/Api/WebhookApi.md#webhookcontrollerhandlewebhook)                                    | **POST** /webhook                         |
| _WithdrawApi_       | [**withdrawV2ControllerCreateWithdrawV2**](docs/Api/WithdrawApi.md#withdrawv2controllercreatewithdrawv2)                       | **POST** /withdraw                        |
| _WithdrawApi_       | [**withdrawV2ControllerGetWithdrawV2**](docs/Api/WithdrawApi.md#withdrawv2controllergetwithdrawv2)                             | **GET** /withdraw/{paymentId}             |

## Models

- [AutoConversionRequestV2Dto](docs/Model/AutoConversionRequestV2Dto.md)
- [AutoConversionResponseV2Dto](docs/Model/AutoConversionResponseV2Dto.md)
- [BinanceControllerGetAveragePrice200Response](docs/Model/BinanceControllerGetAveragePrice200Response.md)
- [BinanceControllerGetWithdrawFee200Response](docs/Model/BinanceControllerGetWithdrawFee200Response.md)
- [BinanceControllerValidateSymbol200Response](docs/Model/BinanceControllerValidateSymbol200Response.md)
- [BinanceOrderResponseDto](docs/Model/BinanceOrderResponseDto.md)
- [BinanceWithdrawRequestDto](docs/Model/BinanceWithdrawRequestDto.md)
- [BinanceWithdrawResponseDto](docs/Model/BinanceWithdrawResponseDto.md)
- [BinanceWorkflowConfigDto](docs/Model/BinanceWorkflowConfigDto.md)
- [BinanceWorkflowResponseDto](docs/Model/BinanceWorkflowResponseDto.md)
- [DepositRequestV2Dto](docs/Model/DepositRequestV2Dto.md)
- [DepositResponseV2Dto](docs/Model/DepositResponseV2Dto.md)
- [PaymentResponseV2Dto](docs/Model/PaymentResponseV2Dto.md)
- [PaymentResponseV2DtoCryptoTransactionInfoInner](docs/Model/PaymentResponseV2DtoCryptoTransactionInfoInner.md)
- [WithdrawRequestV2Dto](docs/Model/WithdrawRequestV2Dto.md)
- [WithdrawResponseV2Dto](docs/Model/WithdrawResponseV2Dto.md)
- [WithdrawV2Dto](docs/Model/WithdrawV2Dto.md)
- [WithdrawV2DtoCryptoTransactionInfoInner](docs/Model/WithdrawV2DtoCryptoTransactionInfoInner.md)
- [InitTestParamsDto](docs/Model/InitTestParamsDto.md)
- [KytMonitorNotificationDto](docs/Model/KytMonitorNotificationDto.md)
- [PartnerDto](docs/Model/PartnerDto.md)
- [SideShiftPairResponseDto](docs/Model/SideShiftPairResponseDto.md)
- [SideShiftShiftDepositDto](docs/Model/SideShiftShiftDepositDto.md)
- [SideShiftShiftResponseDto](docs/Model/SideShiftShiftResponseDto.md)
- [SideShiftVariableShiftRequestDto](docs/Model/SideShiftVariableShiftRequestDto.md)
- [SideShiftVariableShiftResponseDto](docs/Model/SideShiftVariableShiftResponseDto.md)
- [SuccessDto](docs/Model/SuccessDto.md)
- [SupportedAssetDto](docs/Model/SupportedAssetDto.md)
- [TokenRequestDto](docs/Model/TokenRequestDto.md)
- [TokenResponseDto](docs/Model/TokenResponseDto.md)

## API Reference

### BerrySdk Class

#### Static Methods

- `init(?array $config = null): ApiSetup` - Initialize the SDK
- `getApi(): ApiSetup` - Get the API setup instance
- `getLogger(): Logger` - Get the logger instance
- `createDeposit(array $params): \OpenAPI\Client\Model\DepositResponseV2Dto` - Create a deposit
- `getDeposit(string $paymentId, int $maxRetries = 2): \OpenAPI\Client\Model\PaymentResponseV2Dto` - Get deposit information
- `createWithdraw(array $params): \OpenAPI\Client\Model\WithdrawResponseV2Dto` - Create a withdrawal
- `getWithdraw(string $paymentId, int $maxRetries = 2): \OpenAPI\Client\Model\WithdrawV2Dto` - Get withdrawal information
- `signPayload(array $payload, string $privateKey): array` - Sign a payload
- `verifySignature(array $dataWithSignature, string $publicKey): bool` - Verify a signature
- `reset(): void` - Reset the SDK instance

### ApiSetup Class

#### Methods

- `getInstance(): ApiSetup` - Get singleton instance
- `init(?array $config = null): ApiSetup` - Initialize with configuration
- `getAccessToken(): ?string` - Get OAuth access token
- `ensureAccessToken(): string` - Ensure access token is available
- `getConfig(): ?ExternalApiConfig` - Get current configuration
- `getAssetApi(): AssetApi` - Get Asset API instance
- `getAutoConversionApi(): ConvertApi` - Get Auto Conversion API instance
- `getDepositsApi(): DepositsApi` - Get Deposits API instance
- `getWithdrawApi(): WithdrawApi` - Get Withdraw API instance
- `getOauthApi(): OauthApi` - Get OAuth API instance
- `getTestApi(): TestApi` - Get Test API instance

### DepositService Class

#### Methods

- `getSignedDepositRequest(array $params): \OpenAPI\Client\Model\DepositRequestV2Dto` - Get signed deposit request
- `createDeposit(array $params): \OpenAPI\Client\Model\DepositResponseV2Dto` - Create and submit deposit
- `getDeposit(string $paymentId, int $maxRetries = 2): \OpenAPI\Client\Model\PaymentResponseV2Dto` - Get deposit with retry
- `getDepositWithEnvAuth(string $paymentId, int $maxRetries = 2): \OpenAPI\Client\Model\PaymentResponseV2Dto` - Get deposit with env auth

### WithdrawService Class

#### Methods

- `getSignedWithdrawRequest(array $params): \OpenAPI\Client\Model\WithdrawRequestV2Dto` - Get signed withdrawal request
- `createWithdraw(array $params): \OpenAPI\Client\Model\WithdrawResponseV2Dto` - Create and submit withdrawal
- `getWithdraw(string $paymentId, int $maxRetries = 2): \OpenAPI\Client\Model\WithdrawV2Dto` - Get withdrawal with retry
- `getWithdrawWithEnvAuth(string $paymentId, int $maxRetries = 2): \OpenAPI\Client\Model\WithdrawV2Dto` - Get withdrawal with env auth

### CryptoUtils Class

#### Static Methods

- `signPayload(array $payload, string $privateKey): array` - Sign payload with private key
- `verifySignature(array $dataWithSignature, string $publicKey): bool` - Verify signature

## Error Classes

- `BerrySdkError` - Base error class
- `ConfigurationError` - Configuration related errors
- `AuthenticationError` - Authentication related errors
- `ApiError` - API request related errors
- `ValidationError` - Validation related errors
- `CryptoError` - Crypto/signature related errors
- `NetworkError` - Network/connection related errors
- `RateLimitError` - Rate limiting errors
- `BadRequestError` - Bad request errors
- `UnauthorizedError` - Unauthorized errors

## Authorization

Authentication schemes defined for the API:

### bearer

- **Type**: Bearer authentication (JWT)

## Examples

See the `examples/` directory for complete working examples.

## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests
5. Submit a pull request

## License

This project is licensed under the MIT License.

## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `1.15`
  - Generator version: `7.14.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
