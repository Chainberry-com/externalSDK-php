<?php

namespace OpenAPI\Client;

use OpenAPI\Client\Api\AssetApi;
use OpenAPI\Client\Api\ConvertApi;
use OpenAPI\Client\Api\DepositsApi as DepositsApiV2;
use OpenAPI\ClientV1\Api\DepositsApi;
use OpenAPI\Client\Api\OauthApi;
use OpenAPI\Client\Api\TestApi;
use OpenAPI\Client\Api\WithdrawApi as WithdrawApiV2;
use OpenAPI\ClientV1\Api\WithdrawApi;
use OpenAPI\Client\Configuration as ConfigurationV2;
use OpenAPI\Client\Errors\BadRequestError;
use OpenAPI\Client\Errors\ConfigurationError;
use OpenAPI\Client\Errors\NetworkError;
use OpenAPI\Client\Errors\UnauthorizedError;
use OpenAPI\Client\Model\InitTestParamsDto;
use OpenAPI\Client\Model\TokenRequestDto;
use OpenAPI\Client\Utils\CryptoUtils;
use OpenAPI\ClientV1\Api\AutoConversionApi;
use OpenAPI\ClientV1\Configuration;

const DEFAULT_API_VERSION = 'v1';

/**
 * Environment enum
 */
class Environment
{
    public const STAGING = 'staging';
    public const PRODUCTION = 'production';
    public const LOCAL = 'local';
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
    public string $apiVersion = DEFAULT_API_VERSION; // default to v1
}

/**
 * API Setup class for managing API configuration and initialization
 */
class ApiSetup
{
    private static ?ApiSetup $instance = null;
    private ?ExternalApiConfig $config = null;
    private ?Configuration $configuration = null;
    private ?ConfigurationV2 $configurationV2 = null;
    private ?AssetApi $assetApi = null;
    private ?ConvertApi $autoConversionApiV2 = null;
    private ?AutoConversionApi $autoConversionApi = null;
    private ?DepositsApiV2 $depositsApiV2 = null;
    private ?OauthApi $oauthApi = null;
    private ?TestApi $testApi = null;
    private ?WithdrawApiV2 $withdrawApiV2 = null;
    private ?string $accessToken = null;
    private ?DepositsApi $depositsApi = null;
    private ?WithdrawApi $withdrawApi = null;

    private const DEFAULT_BASE_URLS = [
        Environment::STAGING => [
            'v1' => 'https://api-stg.chainberry.com/api/v1',
            'v2' => 'https://api-stg.chainberry.com/api/v2',
        ],
        Environment::PRODUCTION => [
            'v1' => 'https://api.chainberry.com/api/v1',
            'v2' => 'https://api.chainberry.com/api/v2',
        ],
        Environment::LOCAL => [
            'v1' => 'http://192.168.0.226:3001/api/v1',
            'v2' => 'http://192.168.0.226:3001/api/v2',
        ],
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
        Environment::PRODUCTION => "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoxUR0G/8yIIkLWAivkgd
2pMzGyk39uR4O/ZdqJ3vUSsmUBoAHpahzyfn/hT/pZC8/13KA1heB5LdTU91LzWg
667nqd3fFgUx8g4b9BuOWEpBl/VNIbIj/nfU0QxW8s/KSJ8pmIeuYgCqhYiaCpLD
2dpfbf7kMlzXci3kApqTMXiJTO5XHlGhhXpKggHwRIIglsK/lTxnw0MuskywV5gf
V2sj6YxNpGQ+uaY3aO6yTGRU0ZBTrHfxJlsRSeiaTXxX9QhRUPjQRAk5OuLrF5kO
keEKz/U+LCYEW27a3AfFzs0d1D/zIxCEBCft/EwhMHqM+fnSSk/DdxFt63m789IO
BwIDAQAB
-----END PUBLIC KEY-----",
        Environment::LOCAL => "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwvCN/n7etTqjNJ0M0JmU
cGPaXoc6ttN8f3KT02ZV8CpGOV3ekUIwgvXw6mCq4jz2mmGkfnjneuWbWMru3w4I
6xngWdMeJqcVoOviTPn9wuASnsAu4imMxOxRoyLNDqstOg1g0N4wISaCazmCwJXW
LwavgC++lnO/iVXHVln5+DDCSSICkII6RGeYoXMev/SvvV1FoQ7tnC6Z069Uh+Uy
5NYHZrQ/lVIoq9fi0WLrhMzDWYR5ncDjeKntmMb2B2h7Prs3/RXx7bvV1BzSBkE9
nm379RgOvoXx5qiIOZHdk2An9VwH4adrPowZvfcUXuLlNHerWsbAtreAMrw2Eb6s
0QIDAQAB
-----END PUBLIC KEY-----",
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
        $this->configurationV2 = new ConfigurationV2();
        $this->configurationV2->setHost($finalConfig->baseUrl);
        $this->configurationV2->setAccessToken($this->accessToken);

        $this->configuration = new Configuration();
        $this->configuration->setHost($finalConfig->baseUrl);
        $this->configuration->setAccessToken($this->accessToken);

        // Initialize API instances
        $this->assetApi = new AssetApi(null, $this->configurationV2);
        $this->autoConversionApiV2 = new ConvertApi(null, $this->configurationV2);
        $this->depositsApiV2 = new DepositsApiV2(null, $this->configurationV2);
        $this->oauthApi = new OauthApi(null, $this->configurationV2);
        $this->testApi = new TestApi(null, $this->configurationV2);
        $this->withdrawApiV2 = new WithdrawApiV2(null, $this->configurationV2);

        $this->autoConversionApi = new AutoConversionApi(null, $this->configuration);
        $this->depositsApi = new DepositsApi(null, $this->configuration);
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

        $initParams = new InitTestParamsDto([
            'signature' => $signature['signature'],
            'api_token' => $signature['apiToken'],
            'timestamp' => (float) $signature['timestamp'],
        ]);

        $this->testApi->testV2ControllerInitParamsV2($initParams);
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
        $apiVersion = $config['apiVersion'] ?? $envConfig->apiVersion;
        if (!in_array($apiVersion, ['v1', 'v2'])) {
            throw new BadRequestError("Invalid API version. Should be v1 or v2. Please provide either in the config parameter 'apiVersion' or as environment variable CB_API_API_VERSION.");
        }

        $finalConfig = new ExternalApiConfig();
        $finalConfig->environment = $environment;
        $finalConfig->baseUrl = $this->getBaseUrl($environment, $apiVersion);
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
     * @param string $apiVersion API version (defaults to 'v1')
     * @return string|null Base URL
     */
    private function getBaseUrl(?string $environment, string $apiVersion = DEFAULT_API_VERSION): ?string
    {
        if (empty($environment)) {
            return null;
        }
        return self::DEFAULT_BASE_URLS[$environment][$apiVersion] ?? null;
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
        $envApiVersion = getenv('CB_API_VERSION');
        $config->apiVersion = ($envApiVersion !== false) ? $envApiVersion : $config->apiVersion;
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
        if (in_array($normalized, ['local'])) {
            return Environment::LOCAL;
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
            $tokenRequest = new TokenRequestDto([
                'client_id' => $this->config->clientId,
                'client_secret' => $this->config->clientSecret,
                'grant_type' => 'client_credentials',
            ]);

            $response = $this->oauthApi->oAuthV2ControllerTokenV2($tokenRequest);

            if ($response && $response->getAccessToken()) {
                $this->accessToken = $response->getAccessToken();

                if ($this->configuration !== null) {
                    $this->configuration->setAccessToken($this->accessToken);
                }

                if ($this->configurationV2 !== null) {
                    $this->configurationV2->setAccessToken($this->accessToken);
                }

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

        if ($this->configuration === null || $this->configurationV2 === null) {
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
     * Get Auto Conversion API instance
     * 
     * @return ConvertApi
     */
    public function getAutoConversionApiV2(): ConvertApi
    {
        return $this->autoConversionApiV2;
    }

    /**
     * Get Auto Conversion API instance
     * 
     * @return AutoConversionApi
     */
    public function getAutoConversionApi(): AutoConversionApi
    {
        return $this->autoConversionApi;
    }

    /**
     * Get Deposits API instance
     * 
     * @return DepositsApiV2
     */
    public function getDepositsApiV2(): DepositsApiV2
    {
        return $this->depositsApiV2;
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
     * Get Withdraw API instance
     * 
     * @return WithdrawApi
     */
    public function getWithdrawApi(): WithdrawApi
    {
        return $this->withdrawApi;
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
     * @return WithdrawApiV2
     */
    public function getWithdrawApiV2(): WithdrawApiV2
    {
        return $this->withdrawApiV2;
    }

    /**
     * Reset the singleton instance
     */
    public static function reset(): void
    {
        self::$instance = null;
    }
} 