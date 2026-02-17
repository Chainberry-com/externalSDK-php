# OpenAPI\Client\PreWebhookApi



All URIs are relative to http://0.0.0.0:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**preWebhookV2ControllerHandlePreWebhookV2()**](PreWebhookApi.md#preWebhookV2ControllerHandlePreWebhookV2) | **POST** /pre-webhook | Pre-process indexer webhook events |


## `preWebhookV2ControllerHandlePreWebhookV2()`

```php
preWebhookV2ControllerHandlePreWebhookV2($request_body)
```

Pre-process indexer webhook events

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\PreWebhookApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$request_body = array('request_body_example'); // string[]

try {
    $apiInstance->preWebhookV2ControllerHandlePreWebhookV2($request_body);
} catch (Exception $e) {
    echo 'Exception when calling PreWebhookApi->preWebhookV2ControllerHandlePreWebhookV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **request_body** | [**string[]**](../Model/string.md)|  | |

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
