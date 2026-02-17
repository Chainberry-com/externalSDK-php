# OpenAPI\Client\WithdrawApi



All URIs are relative to http://0.0.0.0:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**withdrawV2ControllerCreateWithdrawV2()**](WithdrawApi.md#withdrawV2ControllerCreateWithdrawV2) | **POST** /withdraw |  |
| [**withdrawV2ControllerGetWithdrawV2()**](WithdrawApi.md#withdrawV2ControllerGetWithdrawV2) | **GET** /withdraw/{paymentId} |  |


## `withdrawV2ControllerCreateWithdrawV2()`

```php
withdrawV2ControllerCreateWithdrawV2($withdraw_request_v2_dto): \OpenAPI\Client\Model\WithdrawResponseV2Dto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\WithdrawApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$withdraw_request_v2_dto = new \OpenAPI\Client\Model\WithdrawRequestV2Dto(); // \OpenAPI\Client\Model\WithdrawRequestV2Dto

try {
    $result = $apiInstance->withdrawV2ControllerCreateWithdrawV2($withdraw_request_v2_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WithdrawApi->withdrawV2ControllerCreateWithdrawV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **withdraw_request_v2_dto** | [**\OpenAPI\Client\Model\WithdrawRequestV2Dto**](../Model/WithdrawRequestV2Dto.md)|  | |

### Return type

[**\OpenAPI\Client\Model\WithdrawResponseV2Dto**](../Model/WithdrawResponseV2Dto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `withdrawV2ControllerGetWithdrawV2()`

```php
withdrawV2ControllerGetWithdrawV2($payment_id): \OpenAPI\Client\Model\WithdrawV2Dto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearer
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\WithdrawApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$payment_id = 'payment_id_example'; // string

try {
    $result = $apiInstance->withdrawV2ControllerGetWithdrawV2($payment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WithdrawApi->withdrawV2ControllerGetWithdrawV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **payment_id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\WithdrawV2Dto**](../Model/WithdrawV2Dto.md)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
