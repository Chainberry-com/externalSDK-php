# OpenAPI\Client\KytApi

All URIs are relative to http://localhost:3001/api/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**kytControllerFetchKytStatuses()**](KytApi.md#kytControllerFetchKytStatuses) | **POST** /kyt/fetch-kyt-statuses |  |
| [**kytControllerHandleMonitorNotification()**](KytApi.md#kytControllerHandleMonitorNotification) | **POST** /kyt | Process KYT monitor notification |


## `kytControllerFetchKytStatuses()`

```php
kytControllerFetchKytStatuses($x_auth_apikey, $kyt_fetch_status_dto)
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\KytApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$x_auth_apikey = 'x_auth_apikey_example'; // string | API key for KYT service authentication
$kyt_fetch_status_dto = new \OpenAPI\Client\Model\KytFetchStatusDto(); // \OpenAPI\Client\Model\KytFetchStatusDto

try {
    $apiInstance->kytControllerFetchKytStatuses($x_auth_apikey, $kyt_fetch_status_dto);
} catch (Exception $e) {
    echo 'Exception when calling KytApi->kytControllerFetchKytStatuses: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **x_auth_apikey** | **string**| API key for KYT service authentication | |
| **kyt_fetch_status_dto** | [**\OpenAPI\Client\Model\KytFetchStatusDto**](../Model/KytFetchStatusDto.md)|  | |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `kytControllerHandleMonitorNotification()`

```php
kytControllerHandleMonitorNotification($x_auth_apikey, $kyt_monitor_notification_dto)
```

Process KYT monitor notification

Receives and processes KYT monitor notifications. Requires X-Auth-Apikey header for authentication.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\KytApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$x_auth_apikey = 'x_auth_apikey_example'; // string | API key for KYT service authentication
$kyt_monitor_notification_dto = new \OpenAPI\Client\Model\KytMonitorNotificationDto(); // \OpenAPI\Client\Model\KytMonitorNotificationDto

try {
    $apiInstance->kytControllerHandleMonitorNotification($x_auth_apikey, $kyt_monitor_notification_dto);
} catch (Exception $e) {
    echo 'Exception when calling KytApi->kytControllerHandleMonitorNotification: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **x_auth_apikey** | **string**| API key for KYT service authentication | |
| **kyt_monitor_notification_dto** | [**\OpenAPI\Client\Model\KytMonitorNotificationDto**](../Model/KytMonitorNotificationDto.md)|  | |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
