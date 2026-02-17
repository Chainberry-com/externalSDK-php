# OpenAPI\Client\PartnersApi



All URIs are relative to http://0.0.0.0:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**partnerV2ControllerGetSettingsV2()**](PartnersApi.md#partnerV2ControllerGetSettingsV2) | **GET** /partners/settings/{partnerId} |  |


## `partnerV2ControllerGetSettingsV2()`

```php
partnerV2ControllerGetSettingsV2($partner_id): \OpenAPI\Client\Model\SettingsResponseV2Dto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\PartnersApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$partner_id = 'partner_id_example'; // string

try {
    $result = $apiInstance->partnerV2ControllerGetSettingsV2($partner_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PartnersApi->partnerV2ControllerGetSettingsV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **partner_id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\SettingsResponseV2Dto**](../Model/SettingsResponseV2Dto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
