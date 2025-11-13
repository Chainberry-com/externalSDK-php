# OpenAPI\Client\OauthApi



All URIs are relative to http://localhost:3001/api/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**oAuthV2ControllerTokenV2()**](OauthApi.md#oAuthV2ControllerTokenV2) | **POST** /oauth/token |  |


## `oAuthV2ControllerTokenV2()`

```php
oAuthV2ControllerTokenV2($token_request_dto): \OpenAPI\Client\Model\TokenResponseDto
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\OauthApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$token_request_dto = new \OpenAPI\Client\Model\TokenRequestDto(); // \OpenAPI\Client\Model\TokenRequestDto

try {
    $result = $apiInstance->oAuthV2ControllerTokenV2($token_request_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling OauthApi->oAuthV2ControllerTokenV2: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **token_request_dto** | [**\OpenAPI\Client\Model\TokenRequestDto**](../Model/TokenRequestDto.md)|  | |

### Return type

[**\OpenAPI\Client\Model\TokenResponseDto**](../Model/TokenResponseDto.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
