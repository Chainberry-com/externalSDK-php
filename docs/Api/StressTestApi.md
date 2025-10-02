# OpenAPI\Client\StressTestApi

All URIs are relative to http://localhost:3001/api/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**stressTestControllerTest()**](StressTestApi.md#stressTestControllerTest) | **POST** /stress-test |  |


## `stressTestControllerTest()`

```php
stressTestControllerTest($stress_test_request)
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\StressTestApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$stress_test_request = new \OpenAPI\Client\Model\StressTestRequest(); // \OpenAPI\Client\Model\StressTestRequest

try {
    $apiInstance->stressTestControllerTest($stress_test_request);
} catch (Exception $e) {
    echo 'Exception when calling StressTestApi->stressTestControllerTest: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **stress_test_request** | [**\OpenAPI\Client\Model\StressTestRequest**](../Model/StressTestRequest.md)|  | |

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
