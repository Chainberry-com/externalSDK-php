# OpenAPI\Client\UiCustomizationApi



All URIs are relative to http://0.0.0.0:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**uiCustomizationControllerGetCustomUiV2()**](UiCustomizationApi.md#uiCustomizationControllerGetCustomUiV2) | **GET** /ui-customization/{paymentId} |  |


## `uiCustomizationControllerGetCustomUiV2()`

```php
uiCustomizationControllerGetCustomUiV2($payment_id): \OpenAPI\Client\Model\CustomUiDto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\UiCustomizationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$payment_id = 'payment_id_example'; // string

try {
    $result = $apiInstance->uiCustomizationControllerGetCustomUiV2($payment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling UiCustomizationApi->uiCustomizationControllerGetCustomUiV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **payment_id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\CustomUiDto**](../Model/CustomUiDto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
