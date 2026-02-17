# OpenAPI\Client\AssetApi



All URIs are relative to http://0.0.0.0:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**assetV2ControllerGetSupportedAssetsV2()**](AssetApi.md#assetV2ControllerGetSupportedAssetsV2) | **GET** /asset |  |


## `assetV2ControllerGetSupportedAssetsV2()`

```php
assetV2ControllerGetSupportedAssetsV2(): \OpenAPI\Client\Model\SupportedAssetDto[]
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\AssetApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->assetV2ControllerGetSupportedAssetsV2();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AssetApi->assetV2ControllerGetSupportedAssetsV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\SupportedAssetDto[]**](../Model/SupportedAssetDto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
