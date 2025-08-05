<?php

namespace OpenAPI\Client\Utils;

use OpenAPI\Client\Errors\CryptoError;

/**
 * Crypto utilities for signing and verifying payloads
 */
class CryptoUtils
{
    /**
     * Recursively stringify with sorted keys
     * 
     * @param mixed $obj Object to stringify
     * @return string Canonical string representation
     */
    private static function stringifyWithSortedKeys($obj): string
    {
        if (is_array($obj)) {
            if (empty($obj)) {
                // Empty array - return empty array notation
                return '[]';
            } elseif (array_keys($obj) !== range(0, count($obj) - 1)) {
                // Associative array - sort keys
                ksort($obj);
                $entries = [];
                foreach ($obj as $key => $value) {
                    $entries[] = '"' . $key . '":' . self::stringifyWithSortedKeys($value);
                }
                return '{' . implode(',', $entries) . '}';
            } else {
                // Sequential array
                $entries = [];
                foreach ($obj as $value) {
                    $entries[] = self::stringifyWithSortedKeys($value);
                }
                return '[' . implode(',', $entries) . ']';
            }
        } else {
            return json_encode($obj, JSON_UNESCAPED_SLASHES);
        }
    }

    /**
     * Sign a payload with a private key
     * 
     * @param array $payload Payload to sign
     * @param string $privateKey Private key
     * @return array Signed payload
     * @throws CryptoError
     */
    public static function signPayload(array $payload, string $privateKey): array
    {
        // Clone the payload without any existing signature field
        $payloadToSign = $payload;
        unset($payloadToSign['signature']);

        $canonicalString = self::stringifyWithSortedKeys($payloadToSign);
        
        // Create signature
        $signature = '';
        if (!openssl_sign($canonicalString, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            throw new CryptoError("Failed to sign payload");
        }

        return array_merge($payloadToSign, ['signature' => bin2hex($signature)]);
    }

    /**
     * Verify a signature
     * 
     * @param array $dataWithSignature Data with signature
     * @param string $publicKey Public key
     * @return bool Verification result
     * @throws CryptoError
     */
    public static function verifySignature(array $dataWithSignature, string $publicKey): bool
    {
        if (empty($publicKey)) {
            throw new CryptoError("Public key is required but not found in config");
        }

        $signature = $dataWithSignature['signature'] ?? null;
        if (empty($signature) || !is_string($signature)) {
            return false;
        }

        // Remove signature from data for verification
        $dataToVerify = $dataWithSignature;
        unset($dataToVerify['signature']);

        $canonicalString = self::stringifyWithSortedKeys($dataToVerify);
        $signatureBinary = hex2bin($signature);

        return openssl_verify($canonicalString, $signatureBinary, $publicKey, OPENSSL_ALGO_SHA256) === 1;
    }
} 