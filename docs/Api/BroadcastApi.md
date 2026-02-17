# OpenAPI\Client\BroadcastApi



All URIs are relative to http://0.0.0.0:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**broadcastV2ControllerAddBroadcastUrlV2()**](BroadcastApi.md#broadcastV2ControllerAddBroadcastUrlV2) | **POST** /broadcast | Add a new broadcast webhook URL |
| [**broadcastV2ControllerDeleteBroadcastUrlV2()**](BroadcastApi.md#broadcastV2ControllerDeleteBroadcastUrlV2) | **DELETE** /broadcast/{id} | Delete a broadcast webhook URL |
| [**broadcastV2ControllerGetAllBroadcastUrlsV2()**](BroadcastApi.md#broadcastV2ControllerGetAllBroadcastUrlsV2) | **GET** /broadcast | Get all broadcast webhook URLs |


## `broadcastV2ControllerAddBroadcastUrlV2()`

```php
broadcastV2ControllerAddBroadcastUrlV2($add_broadcast_url_dto): \OpenAPI\Client\Model\BroadcastV2ControllerAddBroadcastUrlV2200Response
```

Add a new broadcast webhook URL

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\BroadcastApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$add_broadcast_url_dto = new \OpenAPI\Client\Model\AddBroadcastUrlDto(); // \OpenAPI\Client\Model\AddBroadcastUrlDto

try {
    $result = $apiInstance->broadcastV2ControllerAddBroadcastUrlV2($add_broadcast_url_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BroadcastApi->broadcastV2ControllerAddBroadcastUrlV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **add_broadcast_url_dto** | [**\OpenAPI\Client\Model\AddBroadcastUrlDto**](../Model/AddBroadcastUrlDto.md)|  | |

### Return type

[**\OpenAPI\Client\Model\BroadcastV2ControllerAddBroadcastUrlV2200Response**](../Model/BroadcastV2ControllerAddBroadcastUrlV2200Response.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `broadcastV2ControllerDeleteBroadcastUrlV2()`

```php
broadcastV2ControllerDeleteBroadcastUrlV2($id)
```

Delete a broadcast webhook URL

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\BroadcastApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$id = 'id_example'; // string

try {
    $apiInstance->broadcastV2ControllerDeleteBroadcastUrlV2($id);
} catch (Exception $e) {
    echo 'Exception when calling BroadcastApi->broadcastV2ControllerDeleteBroadcastUrlV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |

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

## `broadcastV2ControllerGetAllBroadcastUrlsV2()`

```php
broadcastV2ControllerGetAllBroadcastUrlsV2(): \OpenAPI\Client\Model\BroadcastV2ControllerGetAllBroadcastUrlsV2200ResponseInner[]
```

Get all broadcast webhook URLs

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\BroadcastApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->broadcastV2ControllerGetAllBroadcastUrlsV2();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BroadcastApi->broadcastV2ControllerGetAllBroadcastUrlsV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\BroadcastV2ControllerGetAllBroadcastUrlsV2200ResponseInner[]**](../Model/BroadcastV2ControllerGetAllBroadcastUrlsV2200ResponseInner.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
