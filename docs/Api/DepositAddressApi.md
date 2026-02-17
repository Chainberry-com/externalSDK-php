# OpenAPI\Client\DepositAddressApi



All URIs are relative to http://0.0.0.0:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**depositAddressControllerGetDepositAddressV2()**](DepositAddressApi.md#depositAddressControllerGetDepositAddressV2) | **POST** /deposit-address |  |


## `depositAddressControllerGetDepositAddressV2()`

```php
depositAddressControllerGetDepositAddressV2($get_deposit_address_request_dto): \OpenAPI\Client\Model\DepositAddressResponseDto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\DepositAddressApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$get_deposit_address_request_dto = new \OpenAPI\Client\Model\GetDepositAddressRequestDto(); // \OpenAPI\Client\Model\GetDepositAddressRequestDto

try {
    $result = $apiInstance->depositAddressControllerGetDepositAddressV2($get_deposit_address_request_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DepositAddressApi->depositAddressControllerGetDepositAddressV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **get_deposit_address_request_dto** | [**\OpenAPI\Client\Model\GetDepositAddressRequestDto**](../Model/GetDepositAddressRequestDto.md)|  | |

### Return type

[**\OpenAPI\Client\Model\DepositAddressResponseDto**](../Model/DepositAddressResponseDto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
