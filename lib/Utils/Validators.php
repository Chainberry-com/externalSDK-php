<?php

namespace OpenAPI\Client\Utils;

class Validators
{
    public static function email(): Validator
    {
        return (new Validator())
            ->addRule(ValidationRules::required("Email is required"))
            ->addRule(ValidationRules::email());
    }

    public static function url(): Validator
    {
        return (new Validator())
            ->addRule(ValidationRules::required("URL is required"))
            ->addRule(ValidationRules::url());
    }

    public static function amount(): Validator
    {
        return (new Validator())
            ->addRule(ValidationRules::required("Amount is required"))
            ->addRule(ValidationRules::pattern('/^\d+(\.\d+)?$/', "Amount must be a valid number"));
    }

    public static function currency(): Validator
    {
        return (new Validator())
            ->addRule(ValidationRules::required("Currency is required"))
            ->addRule(ValidationRules::pattern('/^[A-Z]{3}$/', "Currency must be a 3-letter code"));
    }

    public static function callbackUrl(): Validator
    {
        return (new Validator())
            ->addRule(ValidationRules::required("Callback URL is required"))
            ->addRule(ValidationRules::url("Callback URL must be a valid URL"));
    }

    public static function tradingAccountLogin(): Validator
    {
        return (new Validator())
            ->addRule(ValidationRules::required("Trading account login is required"))
            ->addRule(ValidationRules::minLength(1, "Trading account login cannot be empty"));
    }
}
