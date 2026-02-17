# OpenAPI\Client\TestApi



All URIs are relative to http://0.0.0.0:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**testV2ControllerInitParamsV2()**](TestApi.md#testV2ControllerInitParamsV2) | **POST** /test/init-params | Test the init params with JWT token and apiToken |
| [**testV2ControllerTestCallbackV2()**](TestApi.md#testV2ControllerTestCallbackV2) | **POST** /test/callback |  |


## `testV2ControllerInitParamsV2()`

```php
testV2ControllerInitParamsV2($init_test_params_dto): \OpenAPI\Client\Model\SuccessDto
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
    $result = $apiInstance->testV2ControllerInitParamsV2($init_test_params_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TestApi->testV2ControllerInitParamsV2: ', $e->getMessage(), PHP_EOL;
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

## `testV2ControllerTestCallbackV2()`

```php
testV2ControllerTestCallbackV2()
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
    $apiInstance->testV2ControllerTestCallbackV2();
} catch (Exception $e) {
    echo 'Exception when calling TestApi->testV2ControllerTestCallbackV2: ', $e->getMessage(), PHP_EOL;
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
