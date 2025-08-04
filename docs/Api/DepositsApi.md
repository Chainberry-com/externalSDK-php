# OpenAPI\Client\DepositsApi

All URIs are relative to http://localhost:3001/api/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**depositControllerCreateDeposit()**](DepositsApi.md#depositControllerCreateDeposit) | **POST** /deposits |  |
| [**depositControllerGetDeposit()**](DepositsApi.md#depositControllerGetDeposit) | **GET** /deposits/{paymentId} |  |
| [**depositControllerGetDepositPayment()**](DepositsApi.md#depositControllerGetDepositPayment) | **GET** /deposits/payments/{paymentId} |  |
| [**depositControllerSetupSupportingAssets()**](DepositsApi.md#depositControllerSetupSupportingAssets) | **GET** /deposits/setup-supporting-assets |  |
| [**depositControllerSyncUpWithFireblocks()**](DepositsApi.md#depositControllerSyncUpWithFireblocks) | **GET** /deposits/sync-up-with-fireblocks |  |


## `depositControllerCreateDeposit()`

```php
depositControllerCreateDeposit($deposit_request): \OpenAPI\Client\Model\DepositDto
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
$deposit_request = new \OpenAPI\Client\Model\DepositRequest(); // \OpenAPI\Client\Model\DepositRequest

try {
    $result = $apiInstance->depositControllerCreateDeposit($deposit_request);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DepositsApi->depositControllerCreateDeposit: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **deposit_request** | [**\OpenAPI\Client\Model\DepositRequest**](../Model/DepositRequest.md)|  | |

### Return type

[**\OpenAPI\Client\Model\DepositDto**](../Model/DepositDto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `depositControllerGetDeposit()`

```php
depositControllerGetDeposit($payment_id): \OpenAPI\Client\Model\GetDepositDto
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
    $result = $apiInstance->depositControllerGetDeposit($payment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DepositsApi->depositControllerGetDeposit: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **payment_id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\GetDepositDto**](../Model/GetDepositDto.md)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `depositControllerGetDepositPayment()`

```php
depositControllerGetDepositPayment($payment_id): \OpenAPI\Client\Model\GetDepositPaymentDto[]
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
    $result = $apiInstance->depositControllerGetDepositPayment($payment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DepositsApi->depositControllerGetDepositPayment: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **payment_id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\GetDepositPaymentDto[]**](../Model/GetDepositPaymentDto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `depositControllerSetupSupportingAssets()`

```php
depositControllerSetupSupportingAssets()
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

try {
    $apiInstance->depositControllerSetupSupportingAssets();
} catch (Exception $e) {
    echo 'Exception when calling DepositsApi->depositControllerSetupSupportingAssets: ', $e->getMessage(), PHP_EOL;
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

## `depositControllerSyncUpWithFireblocks()`

```php
depositControllerSyncUpWithFireblocks()
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

try {
    $apiInstance->depositControllerSyncUpWithFireblocks();
} catch (Exception $e) {
    echo 'Exception when calling DepositsApi->depositControllerSyncUpWithFireblocks: ', $e->getMessage(), PHP_EOL;
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
