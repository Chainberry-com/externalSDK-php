# # DepositRequestV2Dto

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**callback_url** | **string** |  | [optional]
**api_token** | **string** |  |
**timestamp** | **float** |  |
**signature** | **string** |  |
**partner_payment_id** | **string** |  | [optional]
**partner_user_id** | **string** |  | [optional]
**amount** | **string** |  |
**currency** | **string** | Currency for the transaction. Supported currencies: BTC, ETH, USDT, USDC, BNB, POL, SOL, TRX, LTC, TON. Note: Only ETH, USDC, USDT, and BNB have corresponding payment gateways available. |
**network** | **string** | network should be one of the following ETH, BNB, POL, TRX, TON, SOL and align with the currency field. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
