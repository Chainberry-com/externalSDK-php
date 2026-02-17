# OpenAPI\Client\ConvertApi



All URIs are relative to http://0.0.0.0:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**autoConversionV2ControllerAutoConversionV2()**](ConvertApi.md#autoConversionV2ControllerAutoConversionV2) | **POST** /convert |  |


## `autoConversionV2ControllerAutoConversionV2()`

```php
autoConversionV2ControllerAutoConversionV2($auto_conversion_request_v2_dto): \OpenAPI\Client\Model\AutoConversionResponseV2Dto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\ConvertApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$auto_conversion_request_v2_dto = new \OpenAPI\Client\Model\AutoConversionRequestV2Dto(); // \OpenAPI\Client\Model\AutoConversionRequestV2Dto

try {
    $result = $apiInstance->autoConversionV2ControllerAutoConversionV2($auto_conversion_request_v2_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ConvertApi->autoConversionV2ControllerAutoConversionV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **auto_conversion_request_v2_dto** | [**\OpenAPI\Client\Model\AutoConversionRequestV2Dto**](../Model/AutoConversionRequestV2Dto.md)|  | |

### Return type

[**\OpenAPI\Client\Model\AutoConversionResponseV2Dto**](../Model/AutoConversionResponseV2Dto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
