<?php

namespace OpenAPI\Client\Test\Services;

use PHPUnit\Framework\TestCase;
use OpenAPI\Client\Services\WithdrawService;
use OpenAPI\Client\BerrySdk;

/**
 * WithdrawServiceTest Class
 */
class WithdrawServiceTest extends TestCase
{
    private WithdrawService $withdrawService;

    protected function setUp(): void
    {
        $this->withdrawService = new WithdrawService();
    }

    /**
     * Test validation of withdrawal parameters
     */
    public function testValidateCreateWithdrawParams()
    {
        // Test valid parameters
        $validParams = [
            'amount' => '100.00',
            'currency' => 'USDT',
            'callbackUrl' => 'https://example.com/callback',
            'tradingAccountLogin' => 'user123',
            'address' => '0x742d35Cc6634C0532925a3b8D4C9db96C4b4d8b6'
        ];

        // This should not throw an exception
        $this->expectNotToPerformAssertions();
        
        // Use reflection to test private method
        $reflection = new \ReflectionClass($this->withdrawService);
        $method = $reflection->getMethod('validateCreateWithdrawParams');
        $method->setAccessible(true);
        $method->invoke($this->withdrawService, $validParams);
    }

    /**
     * Test validation with missing required parameters
     */
    public function testValidateCreateWithdrawParamsWithMissingAddress()
    {
        $invalidParams = [
            'amount' => '100.00',
            'currency' => 'USDT',
            'callbackUrl' => 'https://example.com/callback',
            'tradingAccountLogin' => 'user123'
            // Missing address
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Address is required');

        $reflection = new \ReflectionClass($this->withdrawService);
        $method = $reflection->getMethod('validateCreateWithdrawParams');
        $method->setAccessible(true);
        $method->invoke($this->withdrawService, $invalidParams);
    }

    /**
     * Test isToken method
     */
    public function testIsToken()
    {
        $reflection = new \ReflectionClass($this->withdrawService);
        $method = $reflection->getMethod('isToken');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($this->withdrawService, 'USDT'));
        $this->assertTrue($method->invoke($this->withdrawService, 'USDC'));
        $this->assertFalse($method->invoke($this->withdrawService, 'BTC'));
        $this->assertFalse($method->invoke($this->withdrawService, 'ETH'));
    }

    /**
     * Test findAssetBySymbol method
     */
    public function testFindAssetBySymbol()
    {
        $supportedAssets = [
            ['symbol' => 'BTC', 'name' => 'Bitcoin'],
            ['symbol' => 'ETH', 'name' => 'Ethereum'],
            ['symbol' => 'USDT', 'name' => 'Tether']
        ];

        $reflection = new \ReflectionClass($this->withdrawService);
        $method = $reflection->getMethod('findAssetBySymbol');
        $method->setAccessible(true);

        $result = $method->invoke($this->withdrawService, $supportedAssets, 'BTC');
        $this->assertEquals(['symbol' => 'BTC', 'name' => 'Bitcoin'], $result);

        $result = $method->invoke($this->withdrawService, $supportedAssets, 'ETH');
        $this->assertEquals(['symbol' => 'ETH', 'name' => 'Ethereum'], $result);

        $result = $method->invoke($this->withdrawService, $supportedAssets, 'INVALID');
        $this->assertNull($result);
    }

    /**
     * Test isAuthenticationError method
     */
    public function testIsAuthenticationError()
    {
        $reflection = new \ReflectionClass($this->withdrawService);
        $method = $reflection->getMethod('isAuthenticationError');
        $method->setAccessible(true);

        // Test with message containing "Unauthorized"
        $error1 = new \Exception("Unauthorized access");
        $this->assertTrue($method->invoke($this->withdrawService, $error1));

        // Test with message containing "401"
        $error2 = new \Exception("HTTP 401 error");
        $this->assertTrue($method->invoke($this->withdrawService, $error2));

        // Test with regular error
        $error3 = new \Exception("Some other error");
        $this->assertFalse($method->invoke($this->withdrawService, $error3));
    }

    /**
     * Test CreateWithdrawParams class
     */
    public function testCreateWithdrawParams()
    {
        $params = new \OpenAPI\Client\Services\CreateWithdrawParams();
        
        $params->amount = '100.00';
        $params->currency = 'USDT';
        $params->callbackUrl = 'https://example.com/callback';
        $params->tradingAccountLogin = 'user123';
        $params->address = '0x742d35Cc6634C0532925a3b8D4C9db96C4b4d8b6';
        $params->paymentGatewayName = 'USDT';
        $params->withdrawCurrency = 'USDT';

        $this->assertEquals('100.00', $params->amount);
        $this->assertEquals('USDT', $params->currency);
        $this->assertEquals('https://example.com/callback', $params->callbackUrl);
        $this->assertEquals('user123', $params->tradingAccountLogin);
        $this->assertEquals('0x742d35Cc6634C0532925a3b8D4C9db96C4b4d8b6', $params->address);
        $this->assertEquals('USDT', $params->paymentGatewayName);
        $this->assertEquals('USDT', $params->withdrawCurrency);
    }
} 