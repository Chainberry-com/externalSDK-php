<?php

namespace OpenAPI\Client;

use OpenAPI\Client\Api\AssetApi;
use OpenAPI\Client\Api\DepositsApi;
use OpenAPI\Client\Api\OauthApi;
use OpenAPI\Client\Api\TestApi;
use OpenAPI\Client\Api\WithdrawApi;
use OpenAPI\Client\Configuration;
use OpenAPI\Client\Errors\BadRequestError;
use OpenAPI\Client\Errors\ConfigurationError;
use OpenAPI\Client\Errors\NetworkError;
use OpenAPI\Client\Errors\UnauthorizedError;
use OpenAPI\Client\Utils\CryptoUtils;

/**
 * Environment enum
 */
class Environment
{
    public const STAGING = 'staging';
    public const PRODUCTION = 'production';
}

/**
 * Setup API configuration
 */
class SetupApiConfig
{
    public ?string $environment = null;
    public ?string $clientId = null;
    public ?string $clientSecret = null;
    public ?string $privateKey = null;
}

/**
 * External API configuration
 */
class ExternalApiConfig
{
    public ?string $environment = null;
    public ?string $baseUrl = null;
    public ?string $clientId = null;
    public ?string $clientSecret = null;
    public ?string $privateKey = null;
    public ?string $chainberryPublicKey = null;
}

/**
 * API Setup class for managing API configuration and initialization
 */
class ApiSetup
{
    private static ?ApiSetup $instance = null;
    private ?ExternalApiConfig $config = null;
    private ?Configuration $configuration = null;
    private ?AssetApi $assetApi = null;
    private ?DepositsApi $depositsApi = null;
    private ?OauthApi $oauthApi = null;
    private ?TestApi $testApi = null;
    private ?WithdrawApi $withdrawApi = null;
    private ?string $accessToken = null;

    private const DEFAULT_BASE_URLS = [
        Environment::STAGING => 'https://api-stg.chainberry.com/api/v1',
        Environment::PRODUCTION => '',
    ];

    private const CHAINBERRY_PUBLIC_KEYS = [
        Environment::STAGING => "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAu9hu+/AZCRVnf3dapRJX
fZEGaIO7vm8OBHhGNW+2T9ZKHYJw5Mid2V5IQ3TSyVMr8GfSiaxK94z7mGXFKfkt
3mQE0MLN8lwTGWC7RdtswUoCB91KNP4vhq06VGLC6F+TlcNoIk4F2o30JwZgixDy
FHUpPXuUTvKte63ur+Na/+yBDsWFPGaxGPNMYKoSaOnJK1cmNj2sr8SqfsNK9LAx
BAWPWFPwzfiIjwOk1udbeMuQ07uRJWwrLevZ7OuMxZvYees/Wg5+25R1OzOY/jis
StNgGTv7bEwE54thP8F+tAeDy8eYhUuotvo+vlpivH49wWN6W3+9gaRskVhrRhEs
0QIDAQAB
-----END PUBLIC KEY-----",
        Environment::PRODUCTION => "",
    ];

    /**
     * Get singleton instance
     * 
     * @return ApiSetup
     */
    public static function getInstance(): ApiSetup
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize the API with optional configuration
     * 
     * @param array|null $config Configuration array
     * @return ApiSetup
     * @throws ConfigurationError
     * @throws NetworkError
     */
    public function init(?array $config = null): ApiSetup
    {
        $finalConfig = $this->mergeConfigWithEnv($config);
        $this->config = $finalConfig;

        // Initialize configuration
        $this->configuration = new Configuration();
        $this->configuration->setHost($finalConfig->baseUrl);
        $this->configuration->setAccessToken($this->accessToken);

        // Initialize API instances
        $this->assetApi = new AssetApi(null, $this->configuration);
        $this->depositsApi = new DepositsApi(null, $this->configuration);
        $this->oauthApi = new OauthApi(null, $this->configuration);
        $this->testApi = new TestApi(null, $this->configuration);
        $this->withdrawApi = new WithdrawApi(null, $this->configuration);

        try {
            $this->testInit();
        } catch (\Exception $error) {
            if ($error instanceof \OpenAPI\Client\Errors\BerrySdkError) {
                throw $error;
            }
            throw new NetworkError("Failed to initialize API connection", ['originalError' => $error->getMessage()]);
        }

        return $this;
    }

    /**
     * Test API initialization
     * 
     * @throws \Exception
     */
    private function testInit(): void
    {
        $this->ensureAccessToken();

        if (empty($this->config->privateKey)) {
            throw new \Exception("Private key is required");
        }

        $signature = CryptoUtils::signPayload([
            'apiToken' => $this->config->clientId,
            'timestamp' => time(),
        ], $this->config->privateKey);

        $this->testApi->testControllerInitParams([
            'signature' => $signature['signature'],
            'apiToken' => $signature['apiToken'],
            'timestamp' => $signature['timestamp'],
        ]);
    }

    /**
     * Merge config with environment variables
     * 
     * @param array|null $config Configuration array
     * @return ExternalApiConfig
     * @throws BadRequestError
     */
    private function mergeConfigWithEnv(?array $config): ExternalApiConfig
    {
        $envConfig = $this->getConfigFromEnv();
        $environment = $config['environment'] ?? $envConfig->environment;

        $finalConfig = new ExternalApiConfig();
        $finalConfig->environment = $environment;
        $finalConfig->baseUrl = $this->getBaseUrl($environment);
        $finalConfig->clientId = $config['clientId'] ?? $envConfig->clientId;
        $finalConfig->clientSecret = $config['clientSecret'] ?? $envConfig->clientSecret;
        $finalConfig->privateKey = $config['privateKey'] ?? $envConfig->privateKey;
        $finalConfig->chainberryPublicKey = $this->getChainberryPublicKey($environment);

        // Validate required configuration
        $missingFields = [];

        if (empty($finalConfig->environment)) {
            $missingFields[] = "Environment is invalid. Should be STAGING or PRODUCTION. Please provide either in the config parameter 'environment' or as environment variable CB_API_ENVIRONMENT.";
        }
        if (empty($finalConfig->clientId)) {
            $missingFields[] = "Client Id is missing. Please provide either in the config parameter 'clientId' or as environment variable CB_API_CLIENT_ID.";
        }
        if (empty($finalConfig->clientSecret)) {
            $missingFields[] = "Client Secret is missing. Please provide either in the config parameter 'clientSecret' or as environment variable CB_API_CLIENT_SECRET.";
        }
        if (empty($finalConfig->privateKey)) {
            $missingFields[] = "Private Key is missing. Please provide either in the config parameter 'privateKey' or as environment variable CB_API_PRIVATE_KEY.";
        }

        if (!empty($missingFields)) {
            throw new BadRequestError("Missing required configuration:\n" . implode("\n", $missingFields));
        }

        return $finalConfig;
    }

    /**
     * Get default base URL based on environment
     * 
     * @param string|null $environment Environment
     * @return string|null Base URL
     */
    private function getBaseUrl(?string $environment): ?string
    {
        if (empty($environment)) {
            return null;
        }
        return self::DEFAULT_BASE_URLS[$environment] ?? null;
    }

    /**
     * Get Chainberry public key for environment
     * 
     * @param string|null $environment Environment
     * @return string Public key
     */
    private function getChainberryPublicKey(?string $environment): string
    {
        if (empty($environment)) {
            return self::CHAINBERRY_PUBLIC_KEYS[Environment::STAGING];
        }
        return self::CHAINBERRY_PUBLIC_KEYS[$environment] ?? self::CHAINBERRY_PUBLIC_KEYS[Environment::STAGING];
    }

    /**
     * Get configuration from environment variables
     * 
     * @return ExternalApiConfig
     */
    private function getConfigFromEnv(): ExternalApiConfig
    {
        $config = new ExternalApiConfig();
        $envEnvironment = getenv('CB_API_ENVIRONMENT');

        $config->environment = $envEnvironment ? $this->parseEnvironment($envEnvironment) : null;
        $config->clientId = getenv('CB_API_CLIENT_ID');
        $config->clientSecret = getenv('CB_API_CLIENT_SECRET');
        $privateKey = getenv('CB_API_PRIVATE_KEY');
        if ($privateKey) {
            // Convert \n to actual newlines if they exist
            $config->privateKey = str_replace('\\n', "\n", $privateKey);
        } else {
            $config->privateKey = null;
        }

        return $config;
    }

    /**
     * Parse environment string to Environment enum
     * 
     * @param string $env Environment string
     * @return string|null Environment
     */
    private function parseEnvironment(string $env): ?string
    {
        $normalized = strtolower($env);
        if (in_array($normalized, ['staging', 'dev', 'development'])) {
            return Environment::STAGING;
        }
        if (in_array($normalized, ['production', 'prod'])) {
            return Environment::PRODUCTION;
        }
        return null;
    }

    /**
     * Get OAuth access token
     * 
     * @return string|null Access token
     * @throws BadRequestError
     */
    public function getAccessToken(): ?string
    {
        $missingVars = [];

        if (empty($this->config->clientId)) {
            $missingVars[] = "Client Id is missing. Please provide either in the config parameter 'clientId' or as environment variable CB_API_CLIENT_ID.";
        }
        if (empty($this->config->clientSecret)) {
            $missingVars[] = "Client Secret is missing. Please provide either in the config parameter 'clientSecret' or as environment variable CB_API_CLIENT_SECRET.";
        }

        if (!empty($missingVars)) {
            throw new BadRequestError("OAuth authentication requires the following environment variables:\n" . implode("\n", $missingVars));
        }

        try {
            $response = $this->oauthApi->oAuthControllerToken([
                'clientId' => $this->config->clientId,
                'clientSecret' => $this->config->clientSecret,
                'grantType' => 'client_credentials',
            ]);

            if ($response && $response->getAccessToken()) {
                $this->accessToken = $response->getAccessToken();
                $this->configuration->setAccessToken($this->accessToken);
                return $this->accessToken;
            }
        } catch (\Exception $error) {
            error_log("Failed to get access token: " . $error->getMessage());
        }

        return null;
    }

    /**
     * Ensure access token is available and set on the API instance
     * 
     * @return string Access token
     * @throws UnauthorizedError
     * @throws ConfigurationError
     */
    public function ensureAccessToken(): string
    {
        $accessToken = $this->getAccessToken();

        if (empty($accessToken)) {
            throw new UnauthorizedError("Failed to obtain access token");
        }

        if ($this->configuration === null) {
            throw new ConfigurationError("API instance is not initialized");
        }

        return $accessToken;
    }

    /**
     * Get the current configuration
     * 
     * @return ExternalApiConfig|null Configuration
     */
    public function getConfig(): ?ExternalApiConfig
    {
        return $this->config;
    }

    /**
     * Get Asset API instance
     * 
     * @return AssetApi
     */
    public function getAssetApi(): AssetApi
    {
        return $this->assetApi;
    }

    /**
     * Get Deposits API instance
     * 
     * @return DepositsApi
     */
    public function getDepositsApi(): DepositsApi
    {
        return $this->depositsApi;
    }

    /**
     * Get OAuth API instance
     * 
     * @return OauthApi
     */
    public function getOauthApi(): OauthApi
    {
        return $this->oauthApi;
    }

    /**
     * Get Test API instance
     * 
     * @return TestApi
     */
    public function getTestApi(): TestApi
    {
        return $this->testApi;
    }

    /**
     * Get Withdraw API instance
     * 
     * @return WithdrawApi
     */
    public function getWithdrawApi(): WithdrawApi
    {
        return $this->withdrawApi;
    }

    /**
     * Reset the singleton instance
     */
    public static function reset(): void
    {
        self::$instance = null;
    }
} 