<?php

require_once __DIR__ . '/../vendor/autoload.php';

use OpenAPI\Client\BerrySdk;
use OpenAPI\Client\Errors\BadRequestError;
use OpenAPI\Client\Errors\UnauthorizedError;
use OpenAPI\Client\Errors\ConfigurationError;
use OpenAPI\Client\Errors\NetworkError;

/**
 * Withdrawal usage example for BerrySdk PHP
 */

echo "=== BerrySdk PHP Withdrawal Example ===\n\n";

// Configuration - you can use environment variables or pass config directly
$config = [
    'environment' => 'staging', // or 'production'
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

    // Example 1: Create a withdrawal
    echo "2. Creating a withdrawal...\n";
    $withdrawParams = [
        'amount' => '50.00',
        'currency' => 'USDT',
        'callbackUrl' => 'https://your-callback-url.com/webhook',
        'tradingAccountLogin' => 'user123',
        'paymentGatewayName' => 'USDT', // Optional
        'withdrawCurrency' => 'USDT',   // Optional
        'address' => '0x742d35Cc6634C0532925a3b8D4C9db96C4b4d8b6' // Required
    ];

    try {
        $withdraw = BerrySdk::createWithdraw($withdrawParams);
        echo "   ✓ Withdrawal created successfully\n";
        echo "   Payment ID: " . ($withdraw['paymentId'] ?? 'N/A') . "\n";
        echo "   Status: " . ($withdraw['status'] ?? 'N/A') . "\n\n";
        
        $paymentId = $withdraw['paymentId'] ?? 'example_payment_id';
    } catch (BadRequestError $e) {
        echo "   ⚠ Bad request error: " . $e->getMessage() . "\n\n";
        $paymentId = 'example_payment_id'; // Use example ID for demo
    } catch (UnauthorizedError $e) {
        echo "   ⚠ Unauthorized error: " . $e->getMessage() . "\n\n";
        $paymentId = 'example_payment_id'; // Use example ID for demo
    }

    // Example 2: Get withdrawal status
    echo "3. Getting withdrawal status...\n";
    try {
        $withdrawStatus = BerrySdk::getWithdraw($paymentId);
        echo "   ✓ Withdrawal status retrieved successfully\n";
        echo "   Payment ID: " . ($withdrawStatus['paymentId'] ?? 'N/A') . "\n";
        echo "   Status: " . ($withdrawStatus['status'] ?? 'N/A') . "\n";
        echo "   Amount: " . ($withdrawStatus['amount'] ?? 'N/A') . "\n";
        echo "   Currency: " . ($withdrawStatus['currency'] ?? 'N/A') . "\n\n";
    } catch (BadRequestError $e) {
        echo "   ⚠ Bad request error: " . $e->getMessage() . "\n\n";
    } catch (UnauthorizedError $e) {
        echo "   ⚠ Unauthorized error: " . $e->getMessage() . "\n\n";
    }

    // Example 3: Using WithdrawService directly
    echo "4. Using WithdrawService directly...\n";
    try {
        $withdrawService = new \OpenAPI\Client\Services\WithdrawService();
        
        // Get signed withdrawal request
        $signedRequest = $withdrawService->getSignedWithdrawRequest($withdrawParams);
        echo "   ✓ Signed withdrawal request generated\n";
        echo "   Signature: " . substr($signedRequest['signature'], 0, 20) . "...\n\n";
        
        // Create withdrawal using service
        $withdrawResult = $withdrawService->createWithdraw($withdrawParams);
        echo "   ✓ Withdrawal created via service\n";
        echo "   Payment ID: " . ($withdrawResult['paymentId'] ?? 'N/A') . "\n\n";
        
    } catch (Exception $e) {
        echo "   ⚠ Error: " . $e->getMessage() . "\n\n";
    }

    // Example 4: Different withdrawal scenarios
    echo "5. Different withdrawal scenarios...\n";
    
    // BTC withdrawal
    $btcWithdrawParams = [
        'amount' => '0.001',
        'currency' => 'BTC',
        'callbackUrl' => 'https://your-callback-url.com/webhook',
        'tradingAccountLogin' => 'user123',
        'address' => 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh'
    ];
    
    try {
        $btcWithdraw = BerrySdk::createWithdraw($btcWithdrawParams);
        echo "   ✓ BTC withdrawal created\n";
        echo "   Payment ID: " . ($btcWithdraw['paymentId'] ?? 'N/A') . "\n\n";
    } catch (Exception $e) {
        echo "   ⚠ BTC withdrawal error: " . $e->getMessage() . "\n\n";
    }
    
    // ETH withdrawal
    $ethWithdrawParams = [
        'amount' => '0.1',
        'currency' => 'ETH',
        'callbackUrl' => 'https://your-callback-url.com/webhook',
        'tradingAccountLogin' => 'user123',
        'address' => '0x742d35Cc6634C0532925a3b8D4C9db96C4b4d8b6'
    ];
    
    try {
        $ethWithdraw = BerrySdk::createWithdraw($ethWithdrawParams);
        echo "   ✓ ETH withdrawal created\n";
        echo "   Payment ID: " . ($ethWithdraw['paymentId'] ?? 'N/A') . "\n\n";
    } catch (Exception $e) {
        echo "   ⚠ ETH withdrawal error: " . $e->getMessage() . "\n\n";
    }

    echo "=== Example completed successfully ===\n";

} catch (ConfigurationError $e) {
    echo "❌ Configuration error: " . $e->getMessage() . "\n";
    echo "Please check your configuration settings.\n";
} catch (NetworkError $e) {
    echo "❌ Network error: " . $e->getMessage() . "\n";
    echo "Please check your internet connection and API endpoint.\n";
} catch (Exception $e) {
    echo "❌ Unexpected error: " . $e->getMessage() . "\n";
    echo "Please check the error details and try again.\n";
}

/**
 * Helper function to print results in a formatted way
 */
function printResult($title, $data) {
    echo "\n--- {$title} ---\n";
    if (is_array($data) || is_object($data)) {
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    } else {
        echo $data;
    }
    echo "\n";
} 