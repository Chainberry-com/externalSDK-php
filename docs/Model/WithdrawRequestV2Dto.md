# # WithdrawRequestV2Dto

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**callback_url** | **string** |  |
**api_token** | **string** |  |
**timestamp** | **float** |  |
**signature** | **string** |  |
**partner_payment_id** | **string** |  | [optional]
**partner_user_id** | **string** |  |
**amount** | **string** | Amount to withdraw. |
**currency** | **string** | Currency for the transaction. Supported currencies: BTC, ETH, USDT, USDC, BNB, POL, TRX, LTC, TON. Note: Only ETH, USDC, USDT, and BNB have corresponding payment gateways available. |
**address** | **string** |  |
**network** | **string** | Network is invalid. It should be one of the following ETH, BNB, POL, TRX, TON and align with the Currency field. | [optional]
**tag** | **string** |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
