# OpenAPIClient-php

External API


## Installation & Usage

### Requirements

PHP 8.1 and later.

### Composer

To install the bindings via [Composer](https://getcomposer.org/), add the following to `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/GIT_USER_ID/GIT_REPO_ID.git"
    }
  ],
  "require": {
    "GIT_USER_ID/GIT_REPO_ID": "*@dev"
  }
}
```

Then run `composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
<?php
require_once('/path/to/OpenAPIClient-php/vendor/autoload.php');
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');




$apiInstance = new OpenAPI\Client\Api\AssetApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->assetControllerGetSupportedAssets();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AssetApi->assetControllerGetSupportedAssets: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *http://localhost:3001/api/v1*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*AssetApi* | [**assetControllerGetSupportedAssets**](docs/Api/AssetApi.md#assetcontrollergetsupportedassets) | **GET** /asset | 
*AutoConversionApi* | [**autoConversionControllerAutoConversion**](docs/Api/AutoConversionApi.md#autoconversioncontrollerautoconversion) | **POST** /auto-conversion | 
*DepositsApi* | [**depositControllerCreateDeposit**](docs/Api/DepositsApi.md#depositcontrollercreatedeposit) | **POST** /deposits | 
*DepositsApi* | [**depositControllerGetDeposit**](docs/Api/DepositsApi.md#depositcontrollergetdeposit) | **GET** /deposits/{paymentId} | 
*DepositsApi* | [**depositControllerGetDepositPayment**](docs/Api/DepositsApi.md#depositcontrollergetdepositpayment) | **GET** /deposits/payments/{paymentId} | 
*DepositsApi* | [**depositControllerSetupSupportingAssets**](docs/Api/DepositsApi.md#depositcontrollersetupsupportingassets) | **GET** /deposits/setup-supporting-assets | 
*DepositsApi* | [**depositControllerSyncUpWithFireblocks**](docs/Api/DepositsApi.md#depositcontrollersyncupwithfireblocks) | **GET** /deposits/sync-up-with-fireblocks | 
*HealthApi* | [**appControllerHealth**](docs/Api/HealthApi.md#appcontrollerhealth) | **GET** /health | Get the health of the API
*KytApi* | [**kytControllerFetchKytStatuses**](docs/Api/KytApi.md#kytcontrollerfetchkytstatuses) | **POST** /kyt/fetch-kyt-statuses | 
*KytApi* | [**kytControllerHandleMonitorNotification**](docs/Api/KytApi.md#kytcontrollerhandlemonitornotification) | **POST** /kyt | Process KYT monitor notification
*OauthApi* | [**oAuthControllerToken**](docs/Api/OauthApi.md#oauthcontrollertoken) | **POST** /oauth/token | 
*PartnersApi* | [**partnerControllerGetCurrentPartner**](docs/Api/PartnersApi.md#partnercontrollergetcurrentpartner) | **GET** /partners/me | 
*PublicKeyApi* | [**publicKeyControllerDownloadPublicKey**](docs/Api/PublicKeyApi.md#publickeycontrollerdownloadpublickey) | **GET** /public-key | 
*SideshiftApi* | [**sideShiftControllerCreateVariableShift**](docs/Api/SideshiftApi.md#sideshiftcontrollercreatevariableshift) | **POST** /sideshift/shifts/variable | 
*SideshiftApi* | [**sideShiftControllerGetPair**](docs/Api/SideshiftApi.md#sideshiftcontrollergetpair) | **GET** /sideshift/pair/{from}/{to} | 
*SideshiftApi* | [**sideShiftControllerGetShift**](docs/Api/SideshiftApi.md#sideshiftcontrollergetshift) | **GET** /sideshift/shifts/{shiftId} | 
*StressTestApi* | [**stressTestControllerTest**](docs/Api/StressTestApi.md#stresstestcontrollertest) | **POST** /stress-test | 
*TestApi* | [**testControllerInitParams**](docs/Api/TestApi.md#testcontrollerinitparams) | **POST** /test/init-params | Test the init params with JWT token and apiToken
*TestApi* | [**testControllerTestCallback**](docs/Api/TestApi.md#testcontrollertestcallback) | **POST** /test/callback | 
*TestApi* | [**testControllerTestJwt**](docs/Api/TestApi.md#testcontrollertestjwt) | **GET** /test/jwt | Test the API with JWT token
*TestApi* | [**testControllerTestSignatureGeneration**](docs/Api/TestApi.md#testcontrollertestsignaturegeneration) | **POST** /test/signature-generation | Test the API with signature generation
*TestApi* | [**testControllerTestSignatureVerificationMiddleware**](docs/Api/TestApi.md#testcontrollertestsignatureverificationmiddleware) | **POST** /test/signature-verification | Test the API with signature verification middleware. Requires apiToken in body. ApiToken is partnerId
*WebhookApi* | [**webhookControllerHandleWebhook**](docs/Api/WebhookApi.md#webhookcontrollerhandlewebhook) | **POST** /webhook | 
*WebhookApi* | [**webhookControllerHandleWebhookV2**](docs/Api/WebhookApi.md#webhookcontrollerhandlewebhookv2) | **POST** /webhook/v2 | 
*WithdrawApi* | [**withdrawControllerCreateWithdraw**](docs/Api/WithdrawApi.md#withdrawcontrollercreatewithdraw) | **POST** /withdraw | 
*WithdrawApi* | [**withdrawControllerGetWithdraw**](docs/Api/WithdrawApi.md#withdrawcontrollergetwithdraw) | **GET** /withdraw/{paymentId} | 

## Models

- [AutoConversionRequestDto](docs/Model/AutoConversionRequestDto.md)
- [AutoConversionResponseDto](docs/Model/AutoConversionResponseDto.md)
- [DepositDto](docs/Model/DepositDto.md)
- [DepositRequest](docs/Model/DepositRequest.md)
- [GetDepositDto](docs/Model/GetDepositDto.md)
- [GetDepositDtoCryptoTransactionInfoInner](docs/Model/GetDepositDtoCryptoTransactionInfoInner.md)
- [GetDepositPaymentDto](docs/Model/GetDepositPaymentDto.md)
- [GetDepositPaymentDtoCryptoTransactionInfoInner](docs/Model/GetDepositPaymentDtoCryptoTransactionInfoInner.md)
- [GetWithdrawDto](docs/Model/GetWithdrawDto.md)
- [InitTestParamsDto](docs/Model/InitTestParamsDto.md)
- [KytFetchStatusDto](docs/Model/KytFetchStatusDto.md)
- [KytMonitorNotificationDto](docs/Model/KytMonitorNotificationDto.md)
- [PartnerDto](docs/Model/PartnerDto.md)
- [SideShiftPairResponseDto](docs/Model/SideShiftPairResponseDto.md)
- [SideShiftShiftDepositDto](docs/Model/SideShiftShiftDepositDto.md)
- [SideShiftShiftResponseDto](docs/Model/SideShiftShiftResponseDto.md)
- [SideShiftVariableShiftRequestDto](docs/Model/SideShiftVariableShiftRequestDto.md)
- [SideShiftVariableShiftResponseDto](docs/Model/SideShiftVariableShiftResponseDto.md)
- [StressTestRequest](docs/Model/StressTestRequest.md)
- [SuccessDto](docs/Model/SuccessDto.md)
- [SupportedAssetDto](docs/Model/SupportedAssetDto.md)
- [TokenRequestDto](docs/Model/TokenRequestDto.md)
- [TokenResponseDto](docs/Model/TokenResponseDto.md)
- [WithdrawDto](docs/Model/WithdrawDto.md)
- [WithdrawRequest](docs/Model/WithdrawRequest.md)

## Authorization

Authentication schemes defined for the API:
### bearer

- **Type**: Bearer authentication (JWT)

## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Author



## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `1.24`
    - Generator version: `7.14.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
