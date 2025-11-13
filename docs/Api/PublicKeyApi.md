# OpenAPI\Client\PublicKeyApi



All URIs are relative to http://localhost:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**publicKeyV2ControllerDownloadPublicKeyV2()**](PublicKeyApi.md#publicKeyV2ControllerDownloadPublicKeyV2) | **GET** /public-key |  |


## `publicKeyV2ControllerDownloadPublicKeyV2()`

```php
publicKeyV2ControllerDownloadPublicKeyV2()
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\PublicKeyApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $apiInstance->publicKeyV2ControllerDownloadPublicKeyV2();
} catch (Exception $e) {
    echo 'Exception when calling PublicKeyApi->publicKeyV2ControllerDownloadPublicKeyV2: ', $e->getMessage(), PHP_EOL;
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
