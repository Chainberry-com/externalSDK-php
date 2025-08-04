<?php

namespace OpenAPI\Client\Test;

use PHPUnit\Framework\TestCase;
use OpenAPI\Client\BerrySdk;
use OpenAPI\Client\Utils\CryptoUtils;
use OpenAPI\Client\Utils\Validators;
use OpenAPI\Client\Errors\ValidationError;

class BerrySdkTest extends TestCase
{
    protected function setUp(): void
    {
        BerrySdk::reset();
    }

    public function testCryptoUtilsSignPayload()
    {
        $payload = ['test' => 'data', 'timestamp' => 1234567890];
        $privateKey = "-----BEGIN PRIVATE KEY-----\n" .
                     "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC7VJTUt9Us8cKB\n" .
                     "7VJTUt9Us8cKB7VJTUt9Us8cKB7VJTUt9Us8cKB7VJTUt9Us8cKB7VJTUt9Us8cKB\n" .
                     "AgMBAAECggEBAKTmjaS6tkK8BlPXClTQ2vpz/N6uxDeS35mXpqasqskVlaUidMB\n" .
                     "MhJ1JG9LuyqirUi+vDlEARxM9eJVO8oPs1q8irZ2B2xP1dXwO2PoIDTf2Wn5/2hDL\n" .
                     "7VJTUt9Us8cKB7VJTUt9Us8cKB7VJTUt9Us8cKB7VJTUt9Us8cKB7VJTUt9Us8cKB\n" .
                     "7VJTUt9Us8cKB7VJTUt9Us8cKB7VJTUt9Us8cKB7VJTUt9Us8cKB7VJTUt9Us8cKB\n" .
                     "7VJTUt9Us8cKB7VJTUt9Us8cKB7VJTUt9Us8cKB7VJTUt9Us8cKB7VJTUt9Us8cKB\n" .
                     "7VJTUt9Us8cKB7VJTUt9Us8cKB7VJTUt9Us8cKB7VJTUt9Us8cKB7VJTUt9Us8cKB\n" .
                     "-----END PRIVATE KEY-----";

        $signed = CryptoUtils::signPayload($payload, $privateKey);
        
        $this->assertArrayHasKey('signature', $signed);
        $this->assertArrayHasKey('test', $signed);
        $this->assertArrayHasKey('timestamp', $signed);
        $this->assertEquals('data', $signed['test']);
        $this->assertEquals(1234567890, $signed['timestamp']);
    }

    public function testValidatorsEmail()
    {
        $validator = Validators::email();
        
        $result = $validator->validate('test@example.com');
        $this->assertTrue($result->isValid);
        
        $result = $validator->validate('invalid-email');
        $this->assertFalse($result->isValid);
    }

    public function testValidatorsUrl()
    {
        $validator = Validators::url();
        
        $result = $validator->validate('https://example.com');
        $this->assertTrue($result->isValid);
        
        $result = $validator->validate('not-a-url');
        $this->assertFalse($result->isValid);
    }

    public function testValidatorsAmount()
    {
        $validator = Validators::amount();
        
        $result = $validator->validate('100.50');
        $this->assertTrue($result->isValid);
        
        $result = $validator->validate('invalid');
        $this->assertFalse($result->isValid);
    }

    public function testValidatorsCurrency()
    {
        $validator = Validators::currency();
        
        $result = $validator->validate('USD');
        $this->assertTrue($result->isValid);
        
        $result = $validator->validate('INVALID');
        $this->assertFalse($result->isValid);
    }

    public function testValidationErrorThrown()
    {
        $this->expectException(ValidationError::class);
        
        $validator = Validators::email();
        $validator->validateAndThrow('invalid-email', 'Email validation');
    }
} 