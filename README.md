# OpenAPIClient-php

API description


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
    $result = $apiInstance->assetV2ControllerGetSupportedAssetsV2();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AssetApi->assetV2ControllerGetSupportedAssetsV2: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *http://0.0.0.0:3001/api/v2*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*AssetApi* | [**assetV2ControllerGetSupportedAssetsV2**](docs/Api/AssetApi.md#assetv2controllergetsupportedassetsv2) | **GET** /asset | 
*BroadcastApi* | [**broadcastV2ControllerAddBroadcastUrlV2**](docs/Api/BroadcastApi.md#broadcastv2controlleraddbroadcasturlv2) | **POST** /broadcast | Add a new broadcast webhook URL
*BroadcastApi* | [**broadcastV2ControllerDeleteBroadcastUrlV2**](docs/Api/BroadcastApi.md#broadcastv2controllerdeletebroadcasturlv2) | **DELETE** /broadcast/{id} | Delete a broadcast webhook URL
*BroadcastApi* | [**broadcastV2ControllerGetAllBroadcastUrlsV2**](docs/Api/BroadcastApi.md#broadcastv2controllergetallbroadcasturlsv2) | **GET** /broadcast | Get all broadcast webhook URLs
*CheckoutsApi* | [**checkoutV2ControllerCreateCheckoutV2**](docs/Api/CheckoutsApi.md#checkoutv2controllercreatecheckoutv2) | **POST** /checkouts | 
*CheckoutsApi* | [**checkoutV2ControllerGetCheckoutPaymentV2**](docs/Api/CheckoutsApi.md#checkoutv2controllergetcheckoutpaymentv2) | **GET** /checkouts/payments/{paymentId} | 
*CheckoutsApi* | [**checkoutV2ControllerGetCheckoutV2**](docs/Api/CheckoutsApi.md#checkoutv2controllergetcheckoutv2) | **GET** /checkouts/{paymentId} | 
*CheckoutsApi* | [**checkoutV2ControllerUpdateCheckoutV2**](docs/Api/CheckoutsApi.md#checkoutv2controllerupdatecheckoutv2) | **PATCH** /checkouts | 
*ConvertApi* | [**autoConversionV2ControllerAutoConversionV2**](docs/Api/ConvertApi.md#autoconversionv2controllerautoconversionv2) | **POST** /convert | 
*DepositAddressApi* | [**depositAddressControllerGetDepositAddressV2**](docs/Api/DepositAddressApi.md#depositaddresscontrollergetdepositaddressv2) | **POST** /deposit-address | 
*DepositsApi* | [**depositV2ControllerCreateDepositV2**](docs/Api/DepositsApi.md#depositv2controllercreatedepositv2) | **POST** /deposits | 
*DepositsApi* | [**depositV2ControllerGetDepositPaymentV2**](docs/Api/DepositsApi.md#depositv2controllergetdepositpaymentv2) | **GET** /deposits/payments/{paymentId} | 
*DepositsApi* | [**depositV2ControllerGetDepositV2**](docs/Api/DepositsApi.md#depositv2controllergetdepositv2) | **GET** /deposits/{paymentId} | 
*KytApi* | [**kytV2ControllerFetchKytStatusesV2**](docs/Api/KytApi.md#kytv2controllerfetchkytstatusesv2) | **POST** /kyt/fetch-kyt-statuses | 
*KytApi* | [**kytV2ControllerHandleMonitorNotificationV2**](docs/Api/KytApi.md#kytv2controllerhandlemonitornotificationv2) | **POST** /kyt | Process KYT monitor notification
*OauthApi* | [**oAuthV2ControllerTokenV2**](docs/Api/OauthApi.md#oauthv2controllertokenv2) | **POST** /oauth/token | 
*PartnersApi* | [**partnerV2ControllerGetSettingsV2**](docs/Api/PartnersApi.md#partnerv2controllergetsettingsv2) | **GET** /partners/settings/{partnerId} | 
*PreWebhookApi* | [**preWebhookV2ControllerHandlePreWebhookV2**](docs/Api/PreWebhookApi.md#prewebhookv2controllerhandleprewebhookv2) | **POST** /pre-webhook | Pre-process indexer webhook events
*PublicKeyApi* | [**publicKeyV2ControllerDownloadPublicKeyV2**](docs/Api/PublicKeyApi.md#publickeyv2controllerdownloadpublickeyv2) | **GET** /public-key | 
*QuicknodeApi* | [**quickNodeControllerSubscribeV2**](docs/Api/QuicknodeApi.md#quicknodecontrollersubscribev2) | **POST** /quicknode/subscribe | 
*TestApi* | [**testV2ControllerInitParamsV2**](docs/Api/TestApi.md#testv2controllerinitparamsv2) | **POST** /test/init-params | Test the init params with JWT token and apiToken
*TestApi* | [**testV2ControllerTestCallbackV2**](docs/Api/TestApi.md#testv2controllertestcallbackv2) | **POST** /test/callback | 
*TestDevApi* | [**devV2ControllerGetCurrentPartnerV2**](docs/Api/TestDevApi.md#devv2controllergetcurrentpartnerv2) | **GET** /test/dev/partners/me | 
*TestDevApi* | [**devV2ControllerTestJwtV2**](docs/Api/TestDevApi.md#devv2controllertestjwtv2) | **GET** /test/dev/jwt | Test the API with JWT token
*TestDevApi* | [**devV2ControllerTestSignatureGenerationV2**](docs/Api/TestDevApi.md#devv2controllertestsignaturegenerationv2) | **POST** /test/dev/signature-generation | Test the API with signature generation
*TestDevApi* | [**devV2ControllerTestSignatureVerificationMiddlewareV2**](docs/Api/TestDevApi.md#devv2controllertestsignatureverificationmiddlewarev2) | **POST** /test/dev/signature-verification | Test the API with signature verification middleware. Requires apiToken in body. ApiToken is partnerId
*TestnetIndexerApi* | [**testnetIndexerControllerSubscribeV2**](docs/Api/TestnetIndexerApi.md#testnetindexercontrollersubscribev2) | **POST** /testnet-indexer/subscribe | 
*TransactionDetailApi* | [**transactionDetailControllerCreateV2**](docs/Api/TransactionDetailApi.md#transactiondetailcontrollercreatev2) | **POST** /transaction-detail | 
*TransactionDetailApi* | [**transactionDetailControllerDeleteV2**](docs/Api/TransactionDetailApi.md#transactiondetailcontrollerdeletev2) | **DELETE** /transaction-detail/{id} | 
*TransactionDetailApi* | [**transactionDetailControllerFindAllV2**](docs/Api/TransactionDetailApi.md#transactiondetailcontrollerfindallv2) | **GET** /transaction-detail | 
*TransactionDetailApi* | [**transactionDetailControllerFindByHashV2**](docs/Api/TransactionDetailApi.md#transactiondetailcontrollerfindbyhashv2) | **GET** /transaction-detail/hash/{hash} | 
*TransactionDetailApi* | [**transactionDetailControllerFindByIdV2**](docs/Api/TransactionDetailApi.md#transactiondetailcontrollerfindbyidv2) | **GET** /transaction-detail/{id} | 
*TransactionDetailApi* | [**transactionDetailControllerUpdateV2**](docs/Api/TransactionDetailApi.md#transactiondetailcontrollerupdatev2) | **PUT** /transaction-detail/{id} | 
*UiCustomizationApi* | [**uiCustomizationControllerGetCustomUiV2**](docs/Api/UiCustomizationApi.md#uicustomizationcontrollergetcustomuiv2) | **GET** /ui-customization/{paymentId} | 
*WebhookApi* | [**webhookV2ControllerHandleWebhookV2**](docs/Api/WebhookApi.md#webhookv2controllerhandlewebhookv2) | **POST** /webhook | 
*WebhookApi* | [**webhookV2ControllerHandleWebhookV2V2**](docs/Api/WebhookApi.md#webhookv2controllerhandlewebhookv2v2) | **POST** /webhook/v2 | 
*WithdrawApi* | [**withdrawV2ControllerCreateWithdrawV2**](docs/Api/WithdrawApi.md#withdrawv2controllercreatewithdrawv2) | **POST** /withdraw | 
*WithdrawApi* | [**withdrawV2ControllerGetWithdrawV2**](docs/Api/WithdrawApi.md#withdrawv2controllergetwithdrawv2) | **GET** /withdraw/{paymentId} | 

## Models

- [AddBroadcastUrlDto](docs/Model/AddBroadcastUrlDto.md)
- [AutoConversionRequestV2Dto](docs/Model/AutoConversionRequestV2Dto.md)
- [AutoConversionResponseV2Dto](docs/Model/AutoConversionResponseV2Dto.md)
- [BroadcastV2ControllerAddBroadcastUrlV2200Response](docs/Model/BroadcastV2ControllerAddBroadcastUrlV2200Response.md)
- [BroadcastV2ControllerGetAllBroadcastUrlsV2200ResponseInner](docs/Model/BroadcastV2ControllerGetAllBroadcastUrlsV2200ResponseInner.md)
- [CheckoutPaymentResponseV2Dto](docs/Model/CheckoutPaymentResponseV2Dto.md)
- [CheckoutResponseV2Dto](docs/Model/CheckoutResponseV2Dto.md)
- [CreateCheckoutRequestDto](docs/Model/CreateCheckoutRequestDto.md)
- [CreateTransactionDetailDto](docs/Model/CreateTransactionDetailDto.md)
- [CustomUiDto](docs/Model/CustomUiDto.md)
- [DepositAddressResponseDto](docs/Model/DepositAddressResponseDto.md)
- [DepositRequestV2Dto](docs/Model/DepositRequestV2Dto.md)
- [DepositResponseV2Dto](docs/Model/DepositResponseV2Dto.md)
- [GetDepositAddressRequestDto](docs/Model/GetDepositAddressRequestDto.md)
- [InitTestParamsDto](docs/Model/InitTestParamsDto.md)
- [KytFetchStatusDto](docs/Model/KytFetchStatusDto.md)
- [KytMonitorNotificationDto](docs/Model/KytMonitorNotificationDto.md)
- [PartnerDto](docs/Model/PartnerDto.md)
- [PartnerResponseV2Dto](docs/Model/PartnerResponseV2Dto.md)
- [PaymentResponseV2Dto](docs/Model/PaymentResponseV2Dto.md)
- [PaymentResponseV2DtoCryptoTransactionInfoInner](docs/Model/PaymentResponseV2DtoCryptoTransactionInfoInner.md)
- [QuickNodeSubscribeDto](docs/Model/QuickNodeSubscribeDto.md)
- [SettingsResponseV2Dto](docs/Model/SettingsResponseV2Dto.md)
- [SettingsResponseV2DtoSupportedAssetsInner](docs/Model/SettingsResponseV2DtoSupportedAssetsInner.md)
- [SubscribeRequestDto](docs/Model/SubscribeRequestDto.md)
- [SubscribeResponseDto](docs/Model/SubscribeResponseDto.md)
- [SuccessDto](docs/Model/SuccessDto.md)
- [SupportedAssetDto](docs/Model/SupportedAssetDto.md)
- [SupportedAssetInfo](docs/Model/SupportedAssetInfo.md)
- [TokenRequestDto](docs/Model/TokenRequestDto.md)
- [TokenResponseDto](docs/Model/TokenResponseDto.md)
- [TransactionDetailResponseDto](docs/Model/TransactionDetailResponseDto.md)
- [UpdateCheckoutRequestDto](docs/Model/UpdateCheckoutRequestDto.md)
- [UpdateTransactionDetailDto](docs/Model/UpdateTransactionDetailDto.md)
- [WithdrawRequestV2Dto](docs/Model/WithdrawRequestV2Dto.md)
- [WithdrawResponseV2Dto](docs/Model/WithdrawResponseV2Dto.md)
- [WithdrawV2Dto](docs/Model/WithdrawV2Dto.md)
- [WithdrawV2DtoCryptoTransactionInfoInner](docs/Model/WithdrawV2DtoCryptoTransactionInfoInner.md)

## Authorization

Authentication schemes defined for the API:
### bearer

- **Type**: Bearer authentication (JWT)

### api-key

- **Type**: API key
- **API key parameter name**: x-api-key
- **Location**: HTTP header


## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Author



## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `2.23`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
