# BerrySdk PHP - Chainberry API SDK

This is a PHP implementation of the berrySdk functionality, providing easy integration with the Chainberry API for deposits, crypto operations, and other API features.

## Features

- **API Setup & Configuration**: Easy initialization with environment variables or configuration arrays
- **Deposit Management**: Create and retrieve deposits with automatic signature generation
- **Withdrawal Management**: Create and retrieve withdrawals with automatic signature generation
- **Dual API Coverage**: Legacy V1 and modern V2 endpoints are both available through a single SDK bootstrap
- **Auto Conversion**: Convert between different cryptocurrencies with automatic rate calculation
- **Crypto Operations**: Sign payloads and verify signatures using RSA-SHA256
- **Error Handling**: Comprehensive error classes for different types of errors
- **Retry Logic**: Built-in retry mechanisms with exponential backoff
- **Validation**: Input validation with customizable rules
- **Logging**: Configurable logging system
- **OAuth Authentication**: Automatic OAuth token management

## Installation

1. Ensure you have PHP 8.1+ installed
2. Install dependencies:

```bash
composer install
```

## Configuration

### Environment Variables

Set the following environment variables:

```bash
export CB_API_ENVIRONMENT=staging  # or production
export CB_API_CLIENT_ID=your_client_id
export CB_API_CLIENT_SECRET=your_client_secret
export CB_API_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----"
```

### Configuration Array

Alternatively, you can pass configuration directly:

```php
$config = [
    'environment' => 'staging', // or 'production'
    'clientId' => 'your_client_id',
    'clientSecret' => 'your_client_secret',
    'privateKey' => '-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----'
];
```

## Choosing an Environment (V1 vs V2)

Set `CB_API_ENVIRONMENT` (or the `$config['environment']` value) to match the API version you want to call. Both generations can coexist in the same project—just initialize once with the environment that matches the endpoints you plan to hit.

| Value          | `Environment::*` constant | API version | Base URL                                       | When to use                                               |
| -------------- | ------------------------- | ----------- | ---------------------------------------------- | --------------------------------------------------------- |
| `staging`      | `Environment::STAGING`    | V1          | `https://api-stg.chainberry.com/api/v1`        | Legacy flows against the staging cluster                  |
| `production`   | `Environment::PRODUCTION` | V1          | `https://api.chainberry.com/api/v1`            | Legacy flows in production                                |
| `local`        | `Environment::LOCAL`      | V1          | `http://192.168.0.226:3001/api/v1`             | Self-hosted/local API gateway                             |
| `staging_v2`   | `Environment::STAGING_V2` | V2          | `https://api-stg.chainberry.com/api/v2`        | New V2 endpoints in staging                               |
| `production_v2`| `Environment::PRODUCTION_V2`| V2        | `https://api.chainberry.com/api/v2`            | New V2 endpoints in production                            |
| `local_v2`     | `Environment::LOCAL_V2`   | V2          | `http://192.168.0.226:3001/api/v2`             | Local development targeting V2 routes                     |

**Tip:** You can call both V1 and V2 helper methods from the same process. Just make sure the environment you initialize with matches the endpoints you want to invoke. For example, if you call `BerrySdk::createWithdrawV2()` while the SDK is initialized with `Environment::STAGING`, the V2 HTTP client will still try to call `/api/v2` on a V1 hostname and authentication will fail.

## Quick Start

### Initialize the SDK (shared for V1 + V2)

```php
<?php

require_once 'vendor/autoload.php';

use OpenAPI\Client\BerrySdk;

// Initialize with environment variables
BerrySdk::init();

// Or initialize with configuration array
BerrySdk::init($config);
```

### V2 Workflow Examples

> Make sure `CB_API_ENVIRONMENT` (or `$config['environment']`) ends with `_v2` before calling these helpers.

#### Create a Deposit (V2)

```php
<?php

use OpenAPI\Client\BerrySdk;

BerrySdk::init();

$depositParams = [
    'amount' => '100.00',
    'currency' => 'USDC',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'network' => 'ETH', // Optional
    'partnerPaymentID' => '1234567890'
];

$deposit = BerrySdk::createDepositV2($depositParams);
echo "Deposit created: " . $deposit->getPaymentId();
```

#### Create a Withdrawal (V2)

```php
<?php

use OpenAPI\Client\BerrySdk;

BerrySdk::init();

$withdrawParams = [
    'amount' => '50.00',
    'currency' => 'USDT',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'address' => '0x742d35Cc6634C0532925a3b8D4C9db96C4b4d8b6', // Required
    'network' => 'TRX',
    'partnerPaymentID' => '1234567890'
];

$withdraw = BerrySdk::createWithdrawV2($withdrawParams);
echo "Withdrawal created: " . $withdraw->getPaymentId();
```

#### Get Deposit Information (V2)

```php
<?php

use OpenAPI\Client\BerrySdk;

BerrySdk::init();

$paymentId = 'your_payment_id';
$deposit = BerrySdk::getDepositV2($paymentId);

echo "Deposit status: " . $deposit->getStatus();
```

#### Get Withdrawal Information (V2)

```php
<?php

use OpenAPI\Client\BerrySdk;

BerrySdk::init();

$paymentId = 'your_payment_id';
$withdraw = BerrySdk::getWithdrawV2($paymentId);

echo "Withdrawal status: " . $withdraw->getStatus();
```

#### Create an Auto Conversion (V2)

```php
<?php

use OpenAPI\Client\BerrySdk;

BerrySdk::init();

$autoConversionParams = [
    'fromAmount' => '100.00',
    'fromCurrency' => 'USDT',
    'toCurrency' => 'USDC',
    'toNetwork' => 'ETH',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'partnerPaymentID' => '1234567890'
];

$autoConversion = BerrySdk::createAutoConversionV2($autoConversionParams);
echo "Auto conversion created: " . $autoConversion->getPaymentId();
```

### V1 Workflow Examples (Legacy)

> Use `staging`, `production`, or `local` for `CB_API_ENVIRONMENT` when calling the legacy helpers below. These are still fully supported for partners that have not migrated to the V2 contracts yet.

#### Create a Deposit (V1)

```php
<?php

use OpenAPI\Client\BerrySdk;

BerrySdk::init();

$deposit = BerrySdk::createDeposit([
    'amount' => '100.00',
    'currency' => 'USDT',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'tradingAccountLogin' => 'user123',
    'address' => '0x742d35Cc6634C0532925a3b8D4C9db96C4b4d8b6'
]);

echo "Deposit created: " . $deposit->getPaymentId();
```

#### Create a Withdrawal (V1)

```php
<?php

use OpenAPI\Client\BerrySdk;

BerrySdk::init();

$withdraw = BerrySdk::createWithdraw([
    'amount' => '50.00',
    'currency' => 'USDT',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'tradingAccountLogin' => 'user123',
    'address' => '0x742d35Cc6634C0532925a3b8D4C9db96C4b4d8b6'
]);

echo "Withdrawal created: " . $withdraw->getPaymentId();
```

#### Get Deposit Information (V1)

```php
<?php

use OpenAPI\Client\BerrySdk;

BerrySdk::init();

$paymentId = 'your_payment_id';
$deposit = BerrySdk::getDeposit($paymentId);

echo "Deposit status: " . $deposit->getStatus();
```

#### Get Withdrawal Information (V1)

```php
<?php

use OpenAPI\Client\BerrySdk;

BerrySdk::init();

$paymentId = 'your_payment_id';
$withdraw = BerrySdk::getWithdraw($paymentId);

echo "Withdrawal status: " . $withdraw->getStatus();
```

#### Create an Auto Conversion (V1)

```php
<?php

use OpenAPI\Client\BerrySdk;

BerrySdk::init();

$autoConversion = BerrySdk::createAutoConversion([
    'amount' => '100.00',
    'currency' => 'USDT',
    'toCurrency' => 'USDC',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'tradingAccountLogin' => 'user123'
]);

echo "Auto conversion created: " . $autoConversion->getPaymentId();
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

```php
<?php

use OpenAPI\Client\Services\DepositServiceV2;

BerrySdk::init();

$depositServiceV2 = new DepositServiceV2();

$signedRequest = $depositServiceV2->getSignedDepositRequest([
    'amount' => '100.00',
    'currency' => 'USDC',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'network' => 'ETH',
]);

$deposit = $depositServiceV2->createDeposit([
    'amount' => '100.00',
    'currency' => 'USDC',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'network' => 'ETH',
]);

$depositInfo = $depositServiceV2->getDeposit('payment_id', 3);
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

```php
<?php

use OpenAPI\Client\Services\WithdrawServiceV2;

BerrySdk::init();

$withdrawServiceV2 = new WithdrawServiceV2();

$signedRequest = $withdrawServiceV2->getSignedWithdrawRequest([
    'amount' => '50.00',
    'currency' => 'USDT',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'address' => '0x742d35Cc6634C0532925a3b8D4C9db96C4b4d8b6',
    'network' => 'TRX',
]);

$withdraw = $withdrawServiceV2->createWithdraw([
    'amount' => '50.00',
    'currency' => 'USDT',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'address' => '0x742d35Cc6634C0532925a3b8D4C9db96C4b4d8b6',
    'network' => 'TRX',
]);

$withdrawInfo = $withdrawServiceV2->getWithdraw('payment_id', 3);
```

### Using the Auto Conversion Service

```php
<?php

use OpenAPI\Client\Services\AutoConversionService;

// Initialize the SDK first
BerrySdk::init();

// Create auto conversion service
$autoConversionService = new AutoConversionService();

// Get signed auto conversion request
$signedRequest = $autoConversionService->getSignedAutoConversionRequest([
    'fromAmount' => '100.00',
    'fromCurrency' => 'USDT',
    'toCurrency' => 'USDC',
    'toNetwork' => 'ETH',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'partnerPaymentID' => '1234567890'
]);

// Create auto conversion
$autoConversion = $autoConversionService->createAutoConversion([
    'fromAmount' => '100.00',
    'fromCurrency' => 'USDT',
    'toCurrency' => 'USDC',
    'toNetwork' => 'ETH',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'partnerPaymentID' => '1234567890'
]);

// Access response properties
echo "Payment ID: " . $autoConversion->getPaymentId();
echo "Checkout URL: " . $autoConversion->getCheckoutUrl();
echo "Conversion Rate: " . $autoConversion->getConversionRate();
echo "Final Currency: " . $autoConversion->getFinalCurrency();
echo "Transaction Amount: " . $autoConversion->getTransactionAmount();
echo "Net Amount: " . $autoConversion->getNetAmount();
echo "Processing Fee: " . $autoConversion->getProcessingFee();
```

```php
<?php

use OpenAPI\Client\Services\AutoConversionServiceV2;

BerrySdk::init();

$autoConversionServiceV2 = new AutoConversionServiceV2();

$signedRequest = $autoConversionServiceV2->getSignedAutoConversionRequest([
    'fromAmount' => '100.00',
    'fromCurrency' => 'USDT',
    'toCurrency' => 'USDC',
    'toNetwork' => 'ETH',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'partnerPaymentID' => '1234567890'
]);

$autoConversion = $autoConversionServiceV2->createAutoConversion([
    'fromAmount' => '100.00',
    'fromCurrency' => 'USDT',
    'toCurrency' => 'USDC',
    'toNetwork' => 'ETH',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'partnerUserID' => 'user123',
    'partnerPaymentID' => '1234567890'
]);

echo "Payment ID: " . $autoConversion->getPaymentId();
echo "Checkout URL: " . $autoConversion->getCheckoutUrl();
echo "Conversion Rate: " . $autoConversion->getConversionRate();
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
- `createAutoConversion(array $params): \OpenAPI\Client\Model\AutoConversionResponseV2Dto` - Create an auto conversion
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

### AutoConversionService Class

#### Methods

- `getSignedAutoConversionRequest(array $params): \OpenAPI\Client\Model\AutoConversionRequestV2Dto` - Get signed auto conversion request
- `createAutoConversion(array $params): \OpenAPI\Client\Model\AutoConversionResponseV2Dto` - Create and submit auto conversion

#### Auto Conversion Parameters (V2)

- `fromAmount` (string, required) - Amount to convert from
- `fromCurrency` (string, required) - Source currency
- `fromNetwork` (string, optional) - Source network
- `toCurrency` (string, required) - Target currency
- `toNetwork` (string, required) - Target network
- `callbackUrl` (string, required) - Webhook URL for notifications
- `partnerUserID` (string, required) - Partner user identifier
- `partnerPaymentID` (string, optional) - Partner payment identifier

#### Auto Conversion Response Properties (V2)

- `getPaymentId()` - Unique payment identifier
- `getCheckoutUrl()` - URL for user to complete the conversion
- `getConversionRate()` - Exchange rate used for conversion
- `getFromAmount()` / `getFromCurrency()` - Original amount/currency
- `getToAmount()` / `getToCurrency()` - Converted amount/currency
- `getCommissionAmount()` / `getCommissionCurrency()` - Commission details

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

## Examples

See the `examples/` directory for complete working examples.

## Testing

```bash
# Run tests
composer test

# Run with specific test
vendor/bin/phpunit --filter testCreateDeposit
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests
5. Submit a pull request

## License

This project is licensed under the MIT License.
