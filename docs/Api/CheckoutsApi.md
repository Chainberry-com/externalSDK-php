# OpenAPI\Client\CheckoutsApi



All URIs are relative to http://0.0.0.0:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**checkoutV2ControllerCreateCheckoutV2()**](CheckoutsApi.md#checkoutV2ControllerCreateCheckoutV2) | **POST** /checkouts |  |
| [**checkoutV2ControllerGetCheckoutPaymentV2()**](CheckoutsApi.md#checkoutV2ControllerGetCheckoutPaymentV2) | **GET** /checkouts/payments/{paymentId} |  |
| [**checkoutV2ControllerGetCheckoutV2()**](CheckoutsApi.md#checkoutV2ControllerGetCheckoutV2) | **GET** /checkouts/{paymentId} |  |
| [**checkoutV2ControllerUpdateCheckoutV2()**](CheckoutsApi.md#checkoutV2ControllerUpdateCheckoutV2) | **PATCH** /checkouts |  |


## `checkoutV2ControllerCreateCheckoutV2()`

```php
checkoutV2ControllerCreateCheckoutV2($create_checkout_request_dto): \OpenAPI\Client\Model\CheckoutResponseV2Dto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CheckoutsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$create_checkout_request_dto = new \OpenAPI\Client\Model\CreateCheckoutRequestDto(); // \OpenAPI\Client\Model\CreateCheckoutRequestDto

try {
    $result = $apiInstance->checkoutV2ControllerCreateCheckoutV2($create_checkout_request_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CheckoutsApi->checkoutV2ControllerCreateCheckoutV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **create_checkout_request_dto** | [**\OpenAPI\Client\Model\CreateCheckoutRequestDto**](../Model/CreateCheckoutRequestDto.md)|  | |

### Return type

[**\OpenAPI\Client\Model\CheckoutResponseV2Dto**](../Model/CheckoutResponseV2Dto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `checkoutV2ControllerGetCheckoutPaymentV2()`

```php
checkoutV2ControllerGetCheckoutPaymentV2($payment_id): \OpenAPI\Client\Model\CheckoutPaymentResponseV2Dto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CheckoutsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$payment_id = 'payment_id_example'; // string

try {
    $result = $apiInstance->checkoutV2ControllerGetCheckoutPaymentV2($payment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CheckoutsApi->checkoutV2ControllerGetCheckoutPaymentV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **payment_id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\CheckoutPaymentResponseV2Dto**](../Model/CheckoutPaymentResponseV2Dto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `checkoutV2ControllerGetCheckoutV2()`

```php
checkoutV2ControllerGetCheckoutV2($payment_id): \OpenAPI\Client\Model\CheckoutResponseV2Dto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearer
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CheckoutsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$payment_id = 'payment_id_example'; // string

try {
    $result = $apiInstance->checkoutV2ControllerGetCheckoutV2($payment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CheckoutsApi->checkoutV2ControllerGetCheckoutV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **payment_id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\CheckoutResponseV2Dto**](../Model/CheckoutResponseV2Dto.md)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `checkoutV2ControllerUpdateCheckoutV2()`

```php
checkoutV2ControllerUpdateCheckoutV2($update_checkout_request_dto): \OpenAPI\Client\Model\CheckoutResponseV2Dto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CheckoutsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$update_checkout_request_dto = new \OpenAPI\Client\Model\UpdateCheckoutRequestDto(); // \OpenAPI\Client\Model\UpdateCheckoutRequestDto

try {
    $result = $apiInstance->checkoutV2ControllerUpdateCheckoutV2($update_checkout_request_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CheckoutsApi->checkoutV2ControllerUpdateCheckoutV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **update_checkout_request_dto** | [**\OpenAPI\Client\Model\UpdateCheckoutRequestDto**](../Model/UpdateCheckoutRequestDto.md)|  | |

### Return type

[**\OpenAPI\Client\Model\CheckoutResponseV2Dto**](../Model/CheckoutResponseV2Dto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
