# OpenAPI\Client\SideshiftApi

All URIs are relative to http://localhost:3001/api/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**sideShiftControllerCreateVariableShift()**](SideshiftApi.md#sideShiftControllerCreateVariableShift) | **POST** /sideshift/shifts/variable |  |
| [**sideShiftControllerGetPair()**](SideshiftApi.md#sideShiftControllerGetPair) | **GET** /sideshift/pair/{from}/{to} |  |
| [**sideShiftControllerGetShift()**](SideshiftApi.md#sideShiftControllerGetShift) | **GET** /sideshift/shifts/{shiftId} |  |


## `sideShiftControllerCreateVariableShift()`

```php
sideShiftControllerCreateVariableShift($side_shift_variable_shift_request_dto): \OpenAPI\Client\Model\SideShiftVariableShiftResponseDto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\SideshiftApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$side_shift_variable_shift_request_dto = new \OpenAPI\Client\Model\SideShiftVariableShiftRequestDto(); // \OpenAPI\Client\Model\SideShiftVariableShiftRequestDto

try {
    $result = $apiInstance->sideShiftControllerCreateVariableShift($side_shift_variable_shift_request_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SideshiftApi->sideShiftControllerCreateVariableShift: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **side_shift_variable_shift_request_dto** | [**\OpenAPI\Client\Model\SideShiftVariableShiftRequestDto**](../Model/SideShiftVariableShiftRequestDto.md)|  | |

### Return type

[**\OpenAPI\Client\Model\SideShiftVariableShiftResponseDto**](../Model/SideShiftVariableShiftResponseDto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `sideShiftControllerGetPair()`

```php
sideShiftControllerGetPair($from, $to, $amount): \OpenAPI\Client\Model\SideShiftPairResponseDto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\SideshiftApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$from = 'from_example'; // string
$to = 'to_example'; // string
$amount = 3.4; // float | Amount to convert

try {
    $result = $apiInstance->sideShiftControllerGetPair($from, $to, $amount);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SideshiftApi->sideShiftControllerGetPair: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **from** | **string**|  | |
| **to** | **string**|  | |
| **amount** | **float**| Amount to convert | [optional] |

### Return type

[**\OpenAPI\Client\Model\SideShiftPairResponseDto**](../Model/SideShiftPairResponseDto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `sideShiftControllerGetShift()`

```php
sideShiftControllerGetShift($shift_id): \OpenAPI\Client\Model\SideShiftShiftResponseDto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\SideshiftApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$shift_id = 'shift_id_example'; // string

try {
    $result = $apiInstance->sideShiftControllerGetShift($shift_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SideshiftApi->sideShiftControllerGetShift: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **shift_id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\SideShiftShiftResponseDto**](../Model/SideShiftShiftResponseDto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
