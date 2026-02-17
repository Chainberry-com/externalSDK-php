# OpenAPI\Client\TransactionDetailApi



All URIs are relative to http://0.0.0.0:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**transactionDetailControllerCreateV2()**](TransactionDetailApi.md#transactionDetailControllerCreateV2) | **POST** /transaction-detail |  |
| [**transactionDetailControllerDeleteV2()**](TransactionDetailApi.md#transactionDetailControllerDeleteV2) | **DELETE** /transaction-detail/{id} |  |
| [**transactionDetailControllerFindAllV2()**](TransactionDetailApi.md#transactionDetailControllerFindAllV2) | **GET** /transaction-detail |  |
| [**transactionDetailControllerFindByHashV2()**](TransactionDetailApi.md#transactionDetailControllerFindByHashV2) | **GET** /transaction-detail/hash/{hash} |  |
| [**transactionDetailControllerFindByIdV2()**](TransactionDetailApi.md#transactionDetailControllerFindByIdV2) | **GET** /transaction-detail/{id} |  |
| [**transactionDetailControllerUpdateV2()**](TransactionDetailApi.md#transactionDetailControllerUpdateV2) | **PUT** /transaction-detail/{id} |  |


## `transactionDetailControllerCreateV2()`

```php
transactionDetailControllerCreateV2($create_transaction_detail_dto): \OpenAPI\Client\Model\TransactionDetailResponseDto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\TransactionDetailApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$create_transaction_detail_dto = new \OpenAPI\Client\Model\CreateTransactionDetailDto(); // \OpenAPI\Client\Model\CreateTransactionDetailDto

try {
    $result = $apiInstance->transactionDetailControllerCreateV2($create_transaction_detail_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TransactionDetailApi->transactionDetailControllerCreateV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **create_transaction_detail_dto** | [**\OpenAPI\Client\Model\CreateTransactionDetailDto**](../Model/CreateTransactionDetailDto.md)|  | |

### Return type

[**\OpenAPI\Client\Model\TransactionDetailResponseDto**](../Model/TransactionDetailResponseDto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `transactionDetailControllerDeleteV2()`

```php
transactionDetailControllerDeleteV2($id)
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearer
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\TransactionDetailApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id = 'id_example'; // string

try {
    $apiInstance->transactionDetailControllerDeleteV2($id);
} catch (Exception $e) {
    echo 'Exception when calling TransactionDetailApi->transactionDetailControllerDeleteV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |

### Return type

void (empty response body)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `transactionDetailControllerFindAllV2()`

```php
transactionDetailControllerFindAllV2($supported_asset_id): \OpenAPI\Client\Model\TransactionDetailResponseDto[]
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearer
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\TransactionDetailApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$supported_asset_id = 'supported_asset_id_example'; // string

try {
    $result = $apiInstance->transactionDetailControllerFindAllV2($supported_asset_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TransactionDetailApi->transactionDetailControllerFindAllV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **supported_asset_id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\TransactionDetailResponseDto[]**](../Model/TransactionDetailResponseDto.md)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `transactionDetailControllerFindByHashV2()`

```php
transactionDetailControllerFindByHashV2($hash): \OpenAPI\Client\Model\TransactionDetailResponseDto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearer
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\TransactionDetailApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hash = 'hash_example'; // string

try {
    $result = $apiInstance->transactionDetailControllerFindByHashV2($hash);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TransactionDetailApi->transactionDetailControllerFindByHashV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **hash** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\TransactionDetailResponseDto**](../Model/TransactionDetailResponseDto.md)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `transactionDetailControllerFindByIdV2()`

```php
transactionDetailControllerFindByIdV2($id): \OpenAPI\Client\Model\TransactionDetailResponseDto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearer
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\TransactionDetailApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id = 'id_example'; // string

try {
    $result = $apiInstance->transactionDetailControllerFindByIdV2($id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TransactionDetailApi->transactionDetailControllerFindByIdV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\TransactionDetailResponseDto**](../Model/TransactionDetailResponseDto.md)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `transactionDetailControllerUpdateV2()`

```php
transactionDetailControllerUpdateV2($id, $update_transaction_detail_dto): \OpenAPI\Client\Model\TransactionDetailResponseDto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearer
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\TransactionDetailApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id = 'id_example'; // string
$update_transaction_detail_dto = new \OpenAPI\Client\Model\UpdateTransactionDetailDto(); // \OpenAPI\Client\Model\UpdateTransactionDetailDto

try {
    $result = $apiInstance->transactionDetailControllerUpdateV2($id, $update_transaction_detail_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TransactionDetailApi->transactionDetailControllerUpdateV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |
| **update_transaction_detail_dto** | [**\OpenAPI\Client\Model\UpdateTransactionDetailDto**](../Model/UpdateTransactionDetailDto.md)|  | |

### Return type

[**\OpenAPI\Client\Model\TransactionDetailResponseDto**](../Model/TransactionDetailResponseDto.md)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
