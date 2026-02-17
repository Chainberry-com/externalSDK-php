# OpenAPI\Client\TestnetIndexerApi



All URIs are relative to http://0.0.0.0:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**testnetIndexerControllerSubscribeV2()**](TestnetIndexerApi.md#testnetIndexerControllerSubscribeV2) | **POST** /testnet-indexer/subscribe |  |


## `testnetIndexerControllerSubscribeV2()`

```php
testnetIndexerControllerSubscribeV2($subscribe_request_dto): \OpenAPI\Client\Model\SubscribeResponseDto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\TestnetIndexerApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$subscribe_request_dto = new \OpenAPI\Client\Model\SubscribeRequestDto(); // \OpenAPI\Client\Model\SubscribeRequestDto

try {
    $result = $apiInstance->testnetIndexerControllerSubscribeV2($subscribe_request_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TestnetIndexerApi->testnetIndexerControllerSubscribeV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **subscribe_request_dto** | [**\OpenAPI\Client\Model\SubscribeRequestDto**](../Model/SubscribeRequestDto.md)|  | |

### Return type

[**\OpenAPI\Client\Model\SubscribeResponseDto**](../Model/SubscribeResponseDto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
