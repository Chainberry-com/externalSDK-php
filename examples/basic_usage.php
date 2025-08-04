<?php

require_once __DIR__ . '/../vendor/autoload.php';

use OpenAPI\Client\BerrySdk;
use OpenAPI\Client\Errors\BadRequestError;
use OpenAPI\Client\Errors\UnauthorizedError;
use OpenAPI\Client\Errors\ConfigurationError;
use OpenAPI\Client\Errors\NetworkError;

/**
 * Basic usage example for BerrySdk PHP
 */

echo "=== BerrySdk PHP Basic Usage Example ===\n\n";

// Configuration - you can use environment variables or pass config directly
$config = [
    'environment' => 'sandbox', // or 'production'
    'clientId' => 'your_client_id_here',
    'clientSecret' => 'your_client_secret_here',
    'privateKey' => "-----BEGIN PRIVATE KEY-----\n" .
                   "Your private key content here\n" .
                   "-----END PRIVATE KEY-----"
];

try {
    // Initialize the SDK
    echo "1. Initializing BerrySdk...\n";
    BerrySdk::init($config);
    echo "   ✓ SDK initialized successfully\n\n";

    // Example 1: Create a deposit
    echo "2. Creating a deposit...\n";
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
        echo "   ✓ Deposit created successfully\n";
        echo "   Payment ID: " . ($deposit['paymentId'] ?? 'N/A') . "\n";
        echo "   Status: " . ($deposit['status'] ?? 'N/A') . "\n\n";
        
        $paymentId = $deposit['paymentId'] ?? 'example_payment_id';
    } catch (BadRequestError $e) {
        echo "   ⚠ Bad request error: " . $e->getMessage() . "\n\n";
        $paymentId = 'example_payment_id'; // Use example ID for demo
    } catch (UnauthorizedError $e) {
        echo "   ⚠ Unauthorized error: " . $e->getMessage() . "\n\n";
        $paymentId = 'example_payment_id'; // Use example ID for demo
    }

    // Example 2: Get deposit information
    echo "3. Getting deposit information...\n";
    try {
        $depositInfo = BerrySdk::getDeposit($paymentId, 2); // 2 retries
        echo "   ✓ Deposit information retrieved\n";
        echo "   Payment ID: " . ($depositInfo['paymentId'] ?? 'N/A') . "\n";
        echo "   Status: " . ($depositInfo['status'] ?? 'N/A') . "\n";
        echo "   Amount: " . ($depositInfo['amount'] ?? 'N/A') . "\n";
        echo "   Currency: " . ($depositInfo['currency'] ?? 'N/A') . "\n\n";
    } catch (Exception $e) {
        echo "   ⚠ Error getting deposit: " . $e->getMessage() . "\n\n";
    }

    // Example 3: Crypto operations
    echo "4. Testing crypto operations...\n";
    
    // Sign a payload
    $payload = [
        'amount' => '100.00',
        'currency' => 'USDT',
        'timestamp' => time(),
        'apiToken' => 'test_token'
    ];

    try {
        $signedPayload = BerrySdk::signPayload($payload, $config['privateKey']);
        echo "   ✓ Payload signed successfully\n";
        echo "   Signature: " . substr($signedPayload['signature'], 0, 20) . "...\n";

        // Verify the signature
        $publicKey = "-----BEGIN PUBLIC KEY-----\n" .
                    "Your public key content here\n" .
                    "-----END PUBLIC KEY-----";
        
        $isValid = BerrySdk::verifySignature($signedPayload, $publicKey);
        echo "   ✓ Signature verification: " . ($isValid ? 'Valid' : 'Invalid') . "\n\n";
    } catch (Exception $e) {
        echo "   ⚠ Crypto operation error: " . $e->getMessage() . "\n\n";
    }

    // Example 4: Using the logger
    echo "5. Testing logger...\n";
    $logger = BerrySdk::getLogger();
    $logger->info('Application started successfully');
    $logger->debug('Debug information', ['config' => 'loaded', 'apis' => 'initialized']);
    echo "   ✓ Logger tested (check your error log for messages)\n\n";

    // Example 5: Error handling demonstration
    echo "6. Error handling demonstration...\n";
    
    // Try to create deposit with invalid parameters
    $invalidParams = [
        'amount' => 'invalid_amount',
        'currency' => 'INVALID',
        'callbackUrl' => 'not_a_url',
        'tradingAccountLogin' => ''
    ];

    try {
        BerrySdk::createDeposit($invalidParams);
    } catch (BadRequestError $e) {
        echo "   ✓ BadRequestError caught: " . $e->getMessage() . "\n";
    } catch (ValidationError $e) {
        echo "   ✓ ValidationError caught: " . $e->getMessage() . "\n";
    } catch (Exception $e) {
        echo "   ✓ General error caught: " . $e->getMessage() . "\n";
    }

    echo "\n=== Example completed successfully ===\n";

} catch (ConfigurationError $e) {
    echo "❌ Configuration error: " . $e->getMessage() . "\n";
    echo "Please check your configuration parameters.\n";
} catch (NetworkError $e) {
    echo "❌ Network error: " . $e->getMessage() . "\n";
    echo "Please check your internet connection and API endpoints.\n";
} catch (Exception $e) {
    echo "❌ Unexpected error: " . $e->getMessage() . "\n";
    echo "Error details: " . $e->getTraceAsString() . "\n";
}

echo "\nNote: This example uses placeholder values. Replace with your actual credentials for real API calls.\n"; 