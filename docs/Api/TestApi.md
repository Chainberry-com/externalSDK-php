# OpenAPI\Client\TestApi

All URIs are relative to http://localhost:3001/api/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**testControllerInitParams()**](TestApi.md#testControllerInitParams) | **POST** /test/init-params | Test the init params with JWT token and apiToken |
| [**testControllerTestCallback()**](TestApi.md#testControllerTestCallback) | **POST** /test/callback |  |
| [**testControllerTestJwt()**](TestApi.md#testControllerTestJwt) | **GET** /test/jwt | Test the API with JWT token |
| [**testControllerTestSignatureGeneration()**](TestApi.md#testControllerTestSignatureGeneration) | **POST** /test/signature-generation | Test the API with signature generation |
| [**testControllerTestSignatureVerificationMiddleware()**](TestApi.md#testControllerTestSignatureVerificationMiddleware) | **POST** /test/signature-verification | Test the API with signature verification middleware. Requires apiToken in body. ApiToken is partnerId |


## `testControllerInitParams()`

```php
testControllerInitParams($init_test_params_dto): \OpenAPI\Client\Model\SuccessDto
```

Test the init params with JWT token and apiToken

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearer
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\TestApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$init_test_params_dto = new \OpenAPI\Client\Model\InitTestParamsDto(); // \OpenAPI\Client\Model\InitTestParamsDto

try {
    $result = $apiInstance->testControllerInitParams($init_test_params_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TestApi->testControllerInitParams: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **init_test_params_dto** | [**\OpenAPI\Client\Model\InitTestParamsDto**](../Model/InitTestParamsDto.md)|  | |

### Return type

[**\OpenAPI\Client\Model\SuccessDto**](../Model/SuccessDto.md)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `testControllerTestCallback()`

```php
testControllerTestCallback()
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\TestApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $apiInstance->testControllerTestCallback();
} catch (Exception $e) {
    echo 'Exception when calling TestApi->testControllerTestCallback: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `testControllerTestJwt()`

```php
testControllerTestJwt(): string
```

Test the API with JWT token

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearer
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\TestApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->testControllerTestJwt();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TestApi->testControllerTestJwt: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

**string**

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `testControllerTestSignatureGeneration()`

```php
testControllerTestSignatureGeneration($body): object
```

Test the API with signature generation

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\TestApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$body = array('key' => new \stdClass); // object

try {
    $result = $apiInstance->testControllerTestSignatureGeneration($body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TestApi->testControllerTestSignatureGeneration: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **body** | **object**|  | |

### Return type

**object**

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `testControllerTestSignatureVerificationMiddleware()`

```php
testControllerTestSignatureVerificationMiddleware($body): bool
```

Test the API with signature verification middleware. Requires apiToken in body. ApiToken is partnerId

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\TestApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$body = array('key' => new \stdClass); // object

try {
    $result = $apiInstance->testControllerTestSignatureVerificationMiddleware($body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TestApi->testControllerTestSignatureVerificationMiddleware: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **body** | **object**|  | |

### Return type

**bool**

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
