# OpenAPI\Client\DepositsApi



All URIs are relative to http://0.0.0.0:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**depositV2ControllerCreateDepositV2()**](DepositsApi.md#depositV2ControllerCreateDepositV2) | **POST** /deposits |  |
| [**depositV2ControllerGetDepositPaymentV2()**](DepositsApi.md#depositV2ControllerGetDepositPaymentV2) | **GET** /deposits/payments/{paymentId} |  |
| [**depositV2ControllerGetDepositV2()**](DepositsApi.md#depositV2ControllerGetDepositV2) | **GET** /deposits/{paymentId} |  |


## `depositV2ControllerCreateDepositV2()`

```php
depositV2ControllerCreateDepositV2($deposit_request_v2_dto): \OpenAPI\Client\Model\DepositResponseV2Dto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\DepositsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$deposit_request_v2_dto = new \OpenAPI\Client\Model\DepositRequestV2Dto(); // \OpenAPI\Client\Model\DepositRequestV2Dto

try {
    $result = $apiInstance->depositV2ControllerCreateDepositV2($deposit_request_v2_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DepositsApi->depositV2ControllerCreateDepositV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **deposit_request_v2_dto** | [**\OpenAPI\Client\Model\DepositRequestV2Dto**](../Model/DepositRequestV2Dto.md)|  | |

### Return type

[**\OpenAPI\Client\Model\DepositResponseV2Dto**](../Model/DepositResponseV2Dto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `depositV2ControllerGetDepositPaymentV2()`

```php
depositV2ControllerGetDepositPaymentV2($payment_id): \OpenAPI\Client\Model\PaymentResponseV2Dto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\DepositsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$payment_id = 'payment_id_example'; // string

try {
    $result = $apiInstance->depositV2ControllerGetDepositPaymentV2($payment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DepositsApi->depositV2ControllerGetDepositPaymentV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **payment_id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\PaymentResponseV2Dto**](../Model/PaymentResponseV2Dto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `depositV2ControllerGetDepositV2()`

```php
depositV2ControllerGetDepositV2($payment_id): \OpenAPI\Client\Model\DepositResponseV2Dto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearer
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\DepositsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$payment_id = 'payment_id_example'; // string

try {
    $result = $apiInstance->depositV2ControllerGetDepositV2($payment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DepositsApi->depositV2ControllerGetDepositV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **payment_id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\DepositResponseV2Dto**](../Model/DepositResponseV2Dto.md)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
