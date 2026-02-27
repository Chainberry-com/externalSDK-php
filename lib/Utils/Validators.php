<?php

namespace OpenAPI\Client\Utils;

class Validators
{
    private const SUPPORTED_NETWORKS = ['ETH', 'BNB', 'POL', 'TRX', 'TON'];

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

    /**
     * @note: Its work work v4 only
     */
    public static function uuid(): Validator
    {
        return (new Validator())
            ->addRule(ValidationRules::required("UUID is required"))
            ->addRule(
                ValidationRules::pattern(
                    '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-4[0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$/',
                    "Value must be a valid UUID v4"
                )
            );
    }

    public static function currency(): Validator
    {
        return (new Validator())
            ->addRule(ValidationRules::required("Currency is required"))
            ->addRule(ValidationRules::pattern('/^[A-Z]{3,5}$/', "Currency must be a 3 to 5 letter uppercase code"));
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

    public static function partnerUserId(): Validator
    {
        return (new Validator())
            ->addRule(ValidationRules::required("Partner user ID is required"))
            ->addRule(ValidationRules::minLength(1, "Partner user ID cannot be empty"));
    }

    public static function network(?string $fieldLabel = 'Network'): Validator
    {
        return (new Validator())
            ->addRule(ValidationRules::required("{$fieldLabel} is required"))
            ->addRule(ValidationRules::oneOf(self::SUPPORTED_NETWORKS, "{$fieldLabel} must be one of: " . implode(', ', self::SUPPORTED_NETWORKS)));
    }

    public static function optionalNetwork(?string $fieldLabel = 'Network'): Validator
    {
        return (new Validator())
            ->addRule(new class(self::SUPPORTED_NETWORKS, $fieldLabel) implements ValidationRule {
                private array $allowed;
                private string $label;

                public function __construct(array $allowed, string $label)
                {
                    $this->allowed = $allowed;
                    $this->label = $label;
                }

                public function validate($value): bool
                {
                    if ($value === null || $value === '') {
                        return true;
                    }

                    return in_array($value, $this->allowed, true);
                }

                public function getMessage(): string
                {
                    return "{$this->label} must be one of: " . implode(', ', $this->allowed);
                }
            });
    }

    public static function address(): Validator
    {
        return (new Validator())
            ->addRule(ValidationRules::required("Address is required"))
            ->addRule(ValidationRules::minLength(1, "Address cannot be empty"));
    }
}
