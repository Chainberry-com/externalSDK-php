# OpenAPI\Client\PartnersApi

All URIs are relative to http://localhost:3001/api/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**partnerControllerGetCurrentPartner()**](PartnersApi.md#partnerControllerGetCurrentPartner) | **GET** /partners/me |  |


## `partnerControllerGetCurrentPartner()`

```php
partnerControllerGetCurrentPartner(): \OpenAPI\Client\Model\PartnerDto
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

try {
    $result = $apiInstance->partnerControllerGetCurrentPartner();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PartnersApi->partnerControllerGetCurrentPartner: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\PartnerDto**](../Model/PartnerDto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
