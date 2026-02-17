# OpenAPI\Client\QuicknodeApi



All URIs are relative to http://0.0.0.0:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**quickNodeControllerSubscribeV2()**](QuicknodeApi.md#quickNodeControllerSubscribeV2) | **POST** /quicknode/subscribe |  |


## `quickNodeControllerSubscribeV2()`

```php
quickNodeControllerSubscribeV2($quick_node_subscribe_dto)
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\QuicknodeApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$quick_node_subscribe_dto = new \OpenAPI\Client\Model\QuickNodeSubscribeDto(); // \OpenAPI\Client\Model\QuickNodeSubscribeDto

try {
    $apiInstance->quickNodeControllerSubscribeV2($quick_node_subscribe_dto);
} catch (Exception $e) {
    echo 'Exception when calling QuicknodeApi->quickNodeControllerSubscribeV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **quick_node_subscribe_dto** | [**\OpenAPI\Client\Model\QuickNodeSubscribeDto**](../Model/QuickNodeSubscribeDto.md)|  | |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
