# OpenAPI\Client\AutoConversionApi

All URIs are relative to http://localhost:3001/api/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**autoConversionControllerAutoConversion()**](AutoConversionApi.md#autoConversionControllerAutoConversion) | **POST** /auto-conversion |  |


## `autoConversionControllerAutoConversion()`

```php
autoConversionControllerAutoConversion($auto_conversion_request_dto): \OpenAPI\Client\Model\AutoConversionResponseDto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\AutoConversionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$auto_conversion_request_dto = new \OpenAPI\Client\Model\AutoConversionRequestDto(); // \OpenAPI\Client\Model\AutoConversionRequestDto

try {
    $result = $apiInstance->autoConversionControllerAutoConversion($auto_conversion_request_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AutoConversionApi->autoConversionControllerAutoConversion: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **auto_conversion_request_dto** | [**\OpenAPI\Client\Model\AutoConversionRequestDto**](../Model/AutoConversionRequestDto.md)|  | |

### Return type

[**\OpenAPI\Client\Model\AutoConversionResponseDto**](../Model/AutoConversionResponseDto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
