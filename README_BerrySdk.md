# BerrySdk PHP - Chainberry API SDK

This is a PHP implementation of the berrySdk functionality, providing easy integration with the Chainberry API for deposits, crypto operations, and other API features.

## Features

- **API Setup & Configuration**: Easy initialization with environment variables or configuration arrays
- **Deposit Management**: Create and retrieve deposits with automatic signature generation
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
export EXTERNAL_API_ENVIRONMENT=sandbox  # or production
export EXTERNAL_API_CLIENT_ID=your_client_id
export EXTERNAL_API_CLIENT_SECRET=your_client_secret
export EXTERNAL_API_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----"
```

### Configuration Array

Alternatively, you can pass configuration directly:

```php
$config = [
    'environment' => 'sandbox', // or 'production'
    'clientId' => 'your_client_id',
    'clientSecret' => 'your_client_secret',
    'privateKey' => '-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----'
];
```

## Quick Start

### Initialize the SDK

```php
<?php

require_once 'vendor/autoload.php';

use OpenAPI\Client\BerrySdk;

// Initialize with environment variables
BerrySdk::init();

// Or initialize with configuration array
BerrySdk::init($config);
```

### Create a Deposit

```php
<?php

use OpenAPI\Client\BerrySdk;

// Initialize the SDK
BerrySdk::init();

// Create a deposit
$depositParams = [
    'amount' => '100.00',
    'currency' => 'USDT',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'tradingAccountLogin' => 'user123',
    'paymentGatewayName' => 'USDT', // Optional
    'paymentCurrency' => 'USDT'     // Optional
];

try {
    $deposit = BerrySdk::createDeposit($depositParams);
    echo "Deposit created: " . $deposit['paymentId'];
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

### Get Deposit Information

```php
<?php

use OpenAPI\Client\BerrySdk;

// Initialize the SDK
BerrySdk::init();

// Get deposit information
$paymentId = 'your_payment_id';

try {
    $deposit = BerrySdk::getDeposit($paymentId);
    echo "Deposit status: " . $deposit['status'];
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
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
    'environment' => Environment::SANDBOX,
    'clientId' => 'your_client_id',
    'clientSecret' => 'your_client_secret',
    'privateKey' => 'your_private_key'
]);

// Get API instances
$assetApi = $apiSetup->getAssetApi();
$depositsApi = $apiSetup->getDepositsApi();
$oauthApi = $apiSetup->getOauthApi();

// Use APIs directly
$supportedAssets = $assetApi->assetControllerGetSupportedAssets();
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
    'tradingAccountLogin' => 'user123'
]);

// Create deposit
$deposit = $depositService->createDeposit([
    'amount' => '100.00',
    'currency' => 'USDT',
    'callbackUrl' => 'https://your-callback-url.com/webhook',
    'tradingAccountLogin' => 'user123'
]);

// Get deposit with retry logic
$depositInfo = $depositService->getDeposit('payment_id', 3); // 3 retries
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
- `createDeposit(array $params): array` - Create a deposit
- `getDeposit(string $paymentId, int $maxRetries = 2): array` - Get deposit information
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
- `getDepositsApi(): DepositsApi` - Get Deposits API instance
- `getOauthApi(): OauthApi` - Get OAuth API instance
- `getTestApi(): TestApi` - Get Test API instance

### DepositService Class

#### Methods

- `getSignedDepositRequest(array $params): array` - Get signed deposit request
- `createDeposit(array $params): array` - Create and submit deposit
- `getDeposit(string $paymentId, int $maxRetries = 2): array` - Get deposit with retry
- `getDepositWithEnvAuth(string $paymentId, int $maxRetries = 2): array` - Get deposit with env auth

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
