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

| Environment  | Constant                  | API Version | Base URL                                | Typical use case           |
| ------------ | ------------------------- | ----------- | --------------------------------------- | -------------------------- |
| `staging`    | `Environment::STAGING`    | `v1`        | `https://api-stg.chainberry.com/api/v1` | Legacy flows in staging    |
| `staging`    | `Environment::STAGING`    | `v2`        | `https://api-stg.chainberry.com/api/v2` | New V2 flows in staging    |
| `production` | `Environment::PRODUCTION` | `v1`        | `https://api.chainberry.com/api/v1`     | Legacy flows in production |
| `production` | `Environment::PRODUCTION` | `v2`        | `https://api.chainberry.com/api/v2`     | New V2 flows in production |
| `local`      | `Environment::LOCAL`      | `v1`        | `http://192.168.0.226:3001/api/v1`      | Local V1 gateway           |
| `local`      | `Environment::LOCAL`      | `v2`        | `http://192.168.0.226:3001/api/v2`      | Local V2 gateway           |

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

// Create a checkout (V2)
$checkout = BerrySdk::createCheckoutV2([
    'amountUsd' => '50.00',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'partnerPaymentID' => 'payment123',
]);

// Fetch existing records (V2)
$depositInfo = BerrySdk::getDepositV2('payment_id');
$withdrawInfo = BerrySdk::getWithdrawV2('payment_id');
$checkoutInfo = BerrySdk::getCheckoutV2('payment_id');
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

### Using the Checkout Service

```php
<?php

use OpenAPI\Client\Services\CheckoutServiceV2;

// Initialize the SDK first
BerrySdk::init();

// Create checkout service
$checkoutService = new CheckoutServiceV2();

// Get signed checkout request
$signedRequest = $checkoutService->getSignedCheckoutRequest([
    'amountUsd' => '50.00',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'partnerPaymentID' => 'payment123',
]);

// Create checkout
$checkout = $checkoutService->createCheckout([
    'amountUsd' => '50.00',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'partnerPaymentID' => 'payment123',
]);

// Update checkout with currency and network
$updatedCheckout = $checkoutService->updateCheckout([
    "paymentID" => 'payment_id',
    "currency" => "USDC",
    "network" => "ETH"
]);

// Get checkout with retry logic
$checkoutInfo = $checkoutService->getCheckout('payment_id', 3); // 3 retries
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

All URIs are relative to *http://0.0.0.0:3001/api/v2*

| Class                  | Method                                                                                                                                  | HTTP request                              | Description                                                                                           |
| ---------------------- | --------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------- | ----------------------------------------------------------------------------------------------------- |
| _AssetApi_             | [**assetV2ControllerGetSupportedAssetsV2**](docs/Api/AssetApi.md#assetv2controllergetsupportedassetsv2)                                 | **GET** /asset                            |
| _BroadcastApi_         | [**broadcastV2ControllerAddBroadcastUrlV2**](docs/Api/BroadcastApi.md#broadcastv2controlleraddbroadcasturlv2)                           | **POST** /broadcast                       | Add a new broadcast webhook URL                                                                       |
| _BroadcastApi_         | [**broadcastV2ControllerDeleteBroadcastUrlV2**](docs/Api/BroadcastApi.md#broadcastv2controllerdeletebroadcasturlv2)                     | **DELETE** /broadcast/{id}                | Delete a broadcast webhook URL                                                                        |
| _BroadcastApi_         | [**broadcastV2ControllerGetAllBroadcastUrlsV2**](docs/Api/BroadcastApi.md#broadcastv2controllergetallbroadcasturlsv2)                   | **GET** /broadcast                        | Get all broadcast webhook URLs                                                                        |
| _CheckoutsApi_         | [**checkoutV2ControllerCreateCheckoutV2**](docs/Api/CheckoutsApi.md#checkoutv2controllercreatecheckoutv2)                               | **POST** /checkouts                       |
| _CheckoutsApi_         | [**checkoutV2ControllerGetCheckoutPaymentV2**](docs/Api/CheckoutsApi.md#checkoutv2controllergetcheckoutpaymentv2)                       | **GET** /checkouts/payments/{paymentId}   |
| _CheckoutsApi_         | [**checkoutV2ControllerGetCheckoutV2**](docs/Api/CheckoutsApi.md#checkoutv2controllergetcheckoutv2)                                     | **GET** /checkouts/{paymentId}            |
| _CheckoutsApi_         | [**checkoutV2ControllerUpdateCheckoutV2**](docs/Api/CheckoutsApi.md#checkoutv2controllerupdatecheckoutv2)                               | **PATCH** /checkouts                      |
| _ConvertApi_           | [**autoConversionV2ControllerAutoConversionV2**](docs/Api/ConvertApi.md#autoconversionv2controllerautoconversionv2)                     | **POST** /convert                         |
| _DepositAddressApi_    | [**depositAddressControllerGetDepositAddressV2**](docs/Api/DepositAddressApi.md#depositaddresscontrollergetdepositaddressv2)            | **POST** /deposit-address                 |
| _DepositsApi_          | [**depositV2ControllerCreateDepositV2**](docs/Api/DepositsApi.md#depositv2controllercreatedepositv2)                                    | **POST** /deposits                        |
| _DepositsApi_          | [**depositV2ControllerGetDepositPaymentV2**](docs/Api/DepositsApi.md#depositv2controllergetdepositpaymentv2)                            | **GET** /deposits/payments/{paymentId}    |
| _DepositsApi_          | [**depositV2ControllerGetDepositV2**](docs/Api/DepositsApi.md#depositv2controllergetdepositv2)                                          | **GET** /deposits/{paymentId}             |
| _KytApi_               | [**kytV2ControllerFetchKytStatusesV2**](docs/Api/KytApi.md#kytv2controllerfetchkytstatusesv2)                                           | **POST** /kyt/fetch-kyt-statuses          |
| _KytApi_               | [**kytV2ControllerHandleMonitorNotificationV2**](docs/Api/KytApi.md#kytv2controllerhandlemonitornotificationv2)                         | **POST** /kyt                             | Process KYT monitor notification                                                                      |
| _OauthApi_             | [**oAuthV2ControllerTokenV2**](docs/Api/OauthApi.md#oauthv2controllertokenv2)                                                           | **POST** /oauth/token                     |
| _PartnersApi_          | [**partnerV2ControllerGetSettingsV2**](docs/Api/PartnersApi.md#partnerv2controllergetsettingsv2)                                        | **GET** /partners/settings/{partnerId}    |
| _PreWebhookApi_        | [**preWebhookV2ControllerHandlePreWebhookV2**](docs/Api/PreWebhookApi.md#prewebhookv2controllerhandleprewebhookv2)                      | **POST** /pre-webhook                     | Pre-process indexer webhook events                                                                    |
| _PublicKeyApi_         | [**publicKeyV2ControllerDownloadPublicKeyV2**](docs/Api/PublicKeyApi.md#publickeyv2controllerdownloadpublickeyv2)                       | **GET** /public-key                       |
| _QuicknodeApi_         | [**quickNodeControllerSubscribeV2**](docs/Api/QuicknodeApi.md#quicknodecontrollersubscribev2)                                           | **POST** /quicknode/subscribe             |
| _TestApi_              | [**testV2ControllerInitParamsV2**](docs/Api/TestApi.md#testv2controllerinitparamsv2)                                                    | **POST** /test/init-params                | Test the init params with JWT token and apiToken                                                      |
| _TestApi_              | [**testV2ControllerTestCallbackV2**](docs/Api/TestApi.md#testv2controllertestcallbackv2)                                                | **POST** /test/callback                   |
| _TestDevApi_           | [**devV2ControllerGetCurrentPartnerV2**](docs/Api/TestDevApi.md#devv2controllergetcurrentpartnerv2)                                     | **GET** /test/dev/partners/me             |
| _TestDevApi_           | [**devV2ControllerTestJwtV2**](docs/Api/TestDevApi.md#devv2controllertestjwtv2)                                                         | **GET** /test/dev/jwt                     | Test the API with JWT token                                                                           |
| _TestDevApi_           | [**devV2ControllerTestSignatureGenerationV2**](docs/Api/TestDevApi.md#devv2controllertestsignaturegenerationv2)                         | **POST** /test/dev/signature-generation   | Test the API with signature generation                                                                |
| _TestDevApi_           | [**devV2ControllerTestSignatureVerificationMiddlewareV2**](docs/Api/TestDevApi.md#devv2controllertestsignatureverificationmiddlewarev2) | **POST** /test/dev/signature-verification | Test the API with signature verification middleware. Requires apiToken in body. ApiToken is partnerId |
| _TestnetIndexerApi_    | [**testnetIndexerControllerSubscribeV2**](docs/Api/TestnetIndexerApi.md#testnetindexercontrollersubscribev2)                            | **POST** /testnet-indexer/subscribe       |
| _TransactionDetailApi_ | [**transactionDetailControllerCreateV2**](docs/Api/TransactionDetailApi.md#transactiondetailcontrollercreatev2)                         | **POST** /transaction-detail              |
| _TransactionDetailApi_ | [**transactionDetailControllerDeleteV2**](docs/Api/TransactionDetailApi.md#transactiondetailcontrollerdeletev2)                         | **DELETE** /transaction-detail/{id}       |
| _TransactionDetailApi_ | [**transactionDetailControllerFindAllV2**](docs/Api/TransactionDetailApi.md#transactiondetailcontrollerfindallv2)                       | **GET** /transaction-detail               |
| _TransactionDetailApi_ | [**transactionDetailControllerFindByHashV2**](docs/Api/TransactionDetailApi.md#transactiondetailcontrollerfindbyhashv2)                 | **GET** /transaction-detail/hash/{hash}   |
| _TransactionDetailApi_ | [**transactionDetailControllerFindByIdV2**](docs/Api/TransactionDetailApi.md#transactiondetailcontrollerfindbyidv2)                     | **GET** /transaction-detail/{id}          |
| _TransactionDetailApi_ | [**transactionDetailControllerUpdateV2**](docs/Api/TransactionDetailApi.md#transactiondetailcontrollerupdatev2)                         | **PUT** /transaction-detail/{id}          |
| _UiCustomizationApi_   | [**uiCustomizationControllerGetCustomUiV2**](docs/Api/UiCustomizationApi.md#uicustomizationcontrollergetcustomuiv2)                     | **GET** /ui-customization/{paymentId}     |
| _WebhookApi_           | [**webhookV2ControllerHandleWebhookV2**](docs/Api/WebhookApi.md#webhookv2controllerhandlewebhookv2)                                     | **POST** /webhook                         |
| _WebhookApi_           | [**webhookV2ControllerHandleWebhookV2V2**](docs/Api/WebhookApi.md#webhookv2controllerhandlewebhookv2v2)                                 | **POST** /webhook/v2                      |
| _WithdrawApi_          | [**withdrawV2ControllerCreateWithdrawV2**](docs/Api/WithdrawApi.md#withdrawv2controllercreatewithdrawv2)                                | **POST** /withdraw                        |
| _WithdrawApi_          | [**withdrawV2ControllerGetWithdrawV2**](docs/Api/WithdrawApi.md#withdrawv2controllergetwithdrawv2)                                      | **GET** /withdraw/{paymentId}             |

## Models

- [AddBroadcastUrlDto](docs/Model/AddBroadcastUrlDto.md)
- [AutoConversionRequestV2Dto](docs/Model/AutoConversionRequestV2Dto.md)
- [AutoConversionResponseV2Dto](docs/Model/AutoConversionResponseV2Dto.md)
- [BroadcastV2ControllerAddBroadcastUrlV2200Response](docs/Model/BroadcastV2ControllerAddBroadcastUrlV2200Response.md)
- [BroadcastV2ControllerGetAllBroadcastUrlsV2200ResponseInner](docs/Model/BroadcastV2ControllerGetAllBroadcastUrlsV2200ResponseInner.md)
- [CheckoutPaymentResponseV2Dto](docs/Model/CheckoutPaymentResponseV2Dto.md)
- [CheckoutResponseV2Dto](docs/Model/CheckoutResponseV2Dto.md)
- [CreateCheckoutRequestDto](docs/Model/CreateCheckoutRequestDto.md)
- [CreateTransactionDetailDto](docs/Model/CreateTransactionDetailDto.md)
- [CustomUiDto](docs/Model/CustomUiDto.md)
- [DepositAddressResponseDto](docs/Model/DepositAddressResponseDto.md)
- [DepositRequestV2Dto](docs/Model/DepositRequestV2Dto.md)
- [DepositResponseV2Dto](docs/Model/DepositResponseV2Dto.md)
- [GetDepositAddressRequestDto](docs/Model/GetDepositAddressRequestDto.md)
- [InitTestParamsDto](docs/Model/InitTestParamsDto.md)
- [KytFetchStatusDto](docs/Model/KytFetchStatusDto.md)
- [KytMonitorNotificationDto](docs/Model/KytMonitorNotificationDto.md)
- [PartnerDto](docs/Model/PartnerDto.md)
- [PartnerResponseV2Dto](docs/Model/PartnerResponseV2Dto.md)
- [PaymentResponseV2Dto](docs/Model/PaymentResponseV2Dto.md)
- [PaymentResponseV2DtoCryptoTransactionInfoInner](docs/Model/PaymentResponseV2DtoCryptoTransactionInfoInner.md)
- [QuickNodeSubscribeDto](docs/Model/QuickNodeSubscribeDto.md)
- [SettingsResponseV2Dto](docs/Model/SettingsResponseV2Dto.md)
- [SettingsResponseV2DtoSupportedAssetsInner](docs/Model/SettingsResponseV2DtoSupportedAssetsInner.md)
- [SubscribeRequestDto](docs/Model/SubscribeRequestDto.md)
- [SubscribeResponseDto](docs/Model/SubscribeResponseDto.md)
- [SuccessDto](docs/Model/SuccessDto.md)
- [SupportedAssetDto](docs/Model/SupportedAssetDto.md)
- [SupportedAssetInfo](docs/Model/SupportedAssetInfo.md)
- [TokenRequestDto](docs/Model/TokenRequestDto.md)
- [TokenResponseDto](docs/Model/TokenResponseDto.md)
- [TransactionDetailResponseDto](docs/Model/TransactionDetailResponseDto.md)
- [UpdateCheckoutRequestDto](docs/Model/UpdateCheckoutRequestDto.md)
- [UpdateTransactionDetailDto](docs/Model/UpdateTransactionDetailDto.md)
- [WithdrawRequestV2Dto](docs/Model/WithdrawRequestV2Dto.md)
- [WithdrawResponseV2Dto](docs/Model/WithdrawResponseV2Dto.md)
- [WithdrawV2Dto](docs/Model/WithdrawV2Dto.md)
- [WithdrawV2DtoCryptoTransactionInfoInner](docs/Model/WithdrawV2DtoCryptoTransactionInfoInner.md)

## Authorization

Authentication schemes defined for the API:

### bearer

- **Type**: Bearer authentication (JWT)

### api-key

- **Type**: API key
- **API key parameter name**: x-api-key
- **Location**: HTTP header

## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Author

## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `2.23`
  - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
