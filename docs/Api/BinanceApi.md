# OpenAPI\Client\BinanceApi

All URIs are relative to http://localhost:3001/api/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**binanceControllerExecuteTradingWorkflow()**](BinanceApi.md#binanceControllerExecuteTradingWorkflow) | **POST** /binance/workflow/execute |  |
| [**binanceControllerGetAveragePrice()**](BinanceApi.md#binanceControllerGetAveragePrice) | **GET** /binance/average-price/{symbol} |  |
| [**binanceControllerGetAveragePrices()**](BinanceApi.md#binanceControllerGetAveragePrices) | **GET** /binance/average-prices |  |
| [**binanceControllerGetCommonAveragePrices()**](BinanceApi.md#binanceControllerGetCommonAveragePrices) | **GET** /binance/common-average-prices |  |
| [**binanceControllerGetWithdrawFee()**](BinanceApi.md#binanceControllerGetWithdrawFee) | **GET** /binance/withdraw-fee/{currency} |  |
| [**binanceControllerValidateSymbol()**](BinanceApi.md#binanceControllerValidateSymbol) | **GET** /binance/validate-symbol/{symbol} |  |
| [**binanceControllerWithdrawToFireblocks()**](BinanceApi.md#binanceControllerWithdrawToFireblocks) | **POST** /binance/withdraw |  |


## `binanceControllerExecuteTradingWorkflow()`

```php
binanceControllerExecuteTradingWorkflow($binance_workflow_config_dto): \OpenAPI\Client\Model\BinanceWorkflowResponseDto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\BinanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$binance_workflow_config_dto = new \OpenAPI\Client\Model\BinanceWorkflowConfigDto(); // \OpenAPI\Client\Model\BinanceWorkflowConfigDto

try {
    $result = $apiInstance->binanceControllerExecuteTradingWorkflow($binance_workflow_config_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BinanceApi->binanceControllerExecuteTradingWorkflow: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **binance_workflow_config_dto** | [**\OpenAPI\Client\Model\BinanceWorkflowConfigDto**](../Model/BinanceWorkflowConfigDto.md)|  | |

### Return type

[**\OpenAPI\Client\Model\BinanceWorkflowResponseDto**](../Model/BinanceWorkflowResponseDto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `binanceControllerGetAveragePrice()`

```php
binanceControllerGetAveragePrice($symbol): \OpenAPI\Client\Model\BinanceControllerGetAveragePrice200Response
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\BinanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$symbol = BTCUSDT; // string | Trading pair symbol

try {
    $result = $apiInstance->binanceControllerGetAveragePrice($symbol);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BinanceApi->binanceControllerGetAveragePrice: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **symbol** | **string**| Trading pair symbol | |

### Return type

[**\OpenAPI\Client\Model\BinanceControllerGetAveragePrice200Response**](../Model/BinanceControllerGetAveragePrice200Response.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `binanceControllerGetAveragePrices()`

```php
binanceControllerGetAveragePrices($symbols): \OpenAPI\Client\Model\BinanceControllerGetAveragePrice200Response[]
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\BinanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$symbols = BTCUSDT,ETHUSDT; // string | Comma-separated list of symbols

try {
    $result = $apiInstance->binanceControllerGetAveragePrices($symbols);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BinanceApi->binanceControllerGetAveragePrices: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **symbols** | **string**| Comma-separated list of symbols | |

### Return type

[**\OpenAPI\Client\Model\BinanceControllerGetAveragePrice200Response[]**](../Model/BinanceControllerGetAveragePrice200Response.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `binanceControllerGetCommonAveragePrices()`

```php
binanceControllerGetCommonAveragePrices(): \OpenAPI\Client\Model\BinanceControllerGetAveragePrice200Response[]
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\BinanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->binanceControllerGetCommonAveragePrices();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BinanceApi->binanceControllerGetCommonAveragePrices: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\BinanceControllerGetAveragePrice200Response[]**](../Model/BinanceControllerGetAveragePrice200Response.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `binanceControllerGetWithdrawFee()`

```php
binanceControllerGetWithdrawFee($currency): \OpenAPI\Client\Model\BinanceControllerGetWithdrawFee200Response
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\BinanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$currency = USDT; // string | Currency to get withdraw fee for

try {
    $result = $apiInstance->binanceControllerGetWithdrawFee($currency);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BinanceApi->binanceControllerGetWithdrawFee: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **currency** | **string**| Currency to get withdraw fee for | |

### Return type

[**\OpenAPI\Client\Model\BinanceControllerGetWithdrawFee200Response**](../Model/BinanceControllerGetWithdrawFee200Response.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `binanceControllerValidateSymbol()`

```php
binanceControllerValidateSymbol($symbol): \OpenAPI\Client\Model\BinanceControllerValidateSymbol200Response
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\BinanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$symbol = BTCUSDT; // string | Trading pair symbol to validate

try {
    $result = $apiInstance->binanceControllerValidateSymbol($symbol);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BinanceApi->binanceControllerValidateSymbol: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **symbol** | **string**| Trading pair symbol to validate | |

### Return type

[**\OpenAPI\Client\Model\BinanceControllerValidateSymbol200Response**](../Model/BinanceControllerValidateSymbol200Response.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `binanceControllerWithdrawToFireblocks()`

```php
binanceControllerWithdrawToFireblocks($binance_withdraw_request_dto): \OpenAPI\Client\Model\BinanceWithdrawResponseDto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\BinanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$binance_withdraw_request_dto = new \OpenAPI\Client\Model\BinanceWithdrawRequestDto(); // \OpenAPI\Client\Model\BinanceWithdrawRequestDto

try {
    $result = $apiInstance->binanceControllerWithdrawToFireblocks($binance_withdraw_request_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BinanceApi->binanceControllerWithdrawToFireblocks: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **binance_withdraw_request_dto** | [**\OpenAPI\Client\Model\BinanceWithdrawRequestDto**](../Model/BinanceWithdrawRequestDto.md)|  | |

### Return type

[**\OpenAPI\Client\Model\BinanceWithdrawResponseDto**](../Model/BinanceWithdrawResponseDto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
