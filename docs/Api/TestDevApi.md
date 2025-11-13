# OpenAPI\Client\TestDevApi



All URIs are relative to http://localhost:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**devV2ControllerGetCurrentPartnerV2()**](TestDevApi.md#devV2ControllerGetCurrentPartnerV2) | **GET** /test/dev/partners/me |  |
| [**devV2ControllerTestJwtV2()**](TestDevApi.md#devV2ControllerTestJwtV2) | **GET** /test/dev/jwt | Test the API with JWT token |
| [**devV2ControllerTestSignatureGenerationV2()**](TestDevApi.md#devV2ControllerTestSignatureGenerationV2) | **POST** /test/dev/signature-generation | Test the API with signature generation |
| [**devV2ControllerTestSignatureVerificationMiddlewareV2()**](TestDevApi.md#devV2ControllerTestSignatureVerificationMiddlewareV2) | **POST** /test/dev/signature-verification | Test the API with signature verification middleware. Requires apiToken in body. ApiToken is partnerId |


## `devV2ControllerGetCurrentPartnerV2()`

```php
devV2ControllerGetCurrentPartnerV2(): \OpenAPI\Client\Model\PartnerDto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\TestDevApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->devV2ControllerGetCurrentPartnerV2();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TestDevApi->devV2ControllerGetCurrentPartnerV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\PartnerDto**](../Model/PartnerDto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `devV2ControllerTestJwtV2()`

```php
devV2ControllerTestJwtV2(): string
```

Test the API with JWT token

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearer
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\TestDevApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->devV2ControllerTestJwtV2();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TestDevApi->devV2ControllerTestJwtV2: ', $e->getMessage(), PHP_EOL;
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

## `devV2ControllerTestSignatureGenerationV2()`

```php
devV2ControllerTestSignatureGenerationV2($body): object
```

Test the API with signature generation

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\TestDevApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$body = array('key' => new \stdClass); // object

try {
    $result = $apiInstance->devV2ControllerTestSignatureGenerationV2($body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TestDevApi->devV2ControllerTestSignatureGenerationV2: ', $e->getMessage(), PHP_EOL;
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

## `devV2ControllerTestSignatureVerificationMiddlewareV2()`

```php
devV2ControllerTestSignatureVerificationMiddlewareV2($body): bool
```

Test the API with signature verification middleware. Requires apiToken in body. ApiToken is partnerId

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\TestDevApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$body = array('key' => new \stdClass); // object

try {
    $result = $apiInstance->devV2ControllerTestSignatureVerificationMiddlewareV2($body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TestDevApi->devV2ControllerTestSignatureVerificationMiddlewareV2: ', $e->getMessage(), PHP_EOL;
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
