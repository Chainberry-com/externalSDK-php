# OpenAPI\Client\WebhookApi



All URIs are relative to http://0.0.0.0:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**webhookV2ControllerHandleWebhookV2()**](WebhookApi.md#webhookV2ControllerHandleWebhookV2) | **POST** /webhook |  |
| [**webhookV2ControllerHandleWebhookV2V2()**](WebhookApi.md#webhookV2ControllerHandleWebhookV2V2) | **POST** /webhook/v2 |  |


## `webhookV2ControllerHandleWebhookV2()`

```php
webhookV2ControllerHandleWebhookV2()
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\WebhookApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $apiInstance->webhookV2ControllerHandleWebhookV2();
} catch (Exception $e) {
    echo 'Exception when calling WebhookApi->webhookV2ControllerHandleWebhookV2: ', $e->getMessage(), PHP_EOL;
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

## `webhookV2ControllerHandleWebhookV2V2()`

```php
webhookV2ControllerHandleWebhookV2V2()
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\WebhookApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $apiInstance->webhookV2ControllerHandleWebhookV2V2();
} catch (Exception $e) {
    echo 'Exception when calling WebhookApi->webhookV2ControllerHandleWebhookV2V2: ', $e->getMessage(), PHP_EOL;
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
