# OpenAPI\Client\WithdrawApi

All URIs are relative to http://localhost:3001/api/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**withdrawControllerCreateWithdraw()**](WithdrawApi.md#withdrawControllerCreateWithdraw) | **POST** /withdraw |  |
| [**withdrawControllerGetWithdraw()**](WithdrawApi.md#withdrawControllerGetWithdraw) | **GET** /withdraw/{paymentId} |  |


## `withdrawControllerCreateWithdraw()`

```php
withdrawControllerCreateWithdraw($withdraw_request): \OpenAPI\Client\Model\WithdrawDto
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
$withdraw_request = new \OpenAPI\Client\Model\WithdrawRequest(); // \OpenAPI\Client\Model\WithdrawRequest

try {
    $result = $apiInstance->withdrawControllerCreateWithdraw($withdraw_request);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WithdrawApi->withdrawControllerCreateWithdraw: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **withdraw_request** | [**\OpenAPI\Client\Model\WithdrawRequest**](../Model/WithdrawRequest.md)|  | |

### Return type

[**\OpenAPI\Client\Model\WithdrawDto**](../Model/WithdrawDto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `withdrawControllerGetWithdraw()`

```php
withdrawControllerGetWithdraw($payment_id): \OpenAPI\Client\Model\GetWithdrawDto
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
    $result = $apiInstance->withdrawControllerGetWithdraw($payment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WithdrawApi->withdrawControllerGetWithdraw: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **payment_id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\GetWithdrawDto**](../Model/GetWithdrawDto.md)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
