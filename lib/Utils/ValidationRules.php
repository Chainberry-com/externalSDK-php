<?php

namespace OpenAPI\Client\Utils;

class ValidationRules
{
    public static function required(string $message = "Value is required"): ValidationRule
    {
        return new class($message) implements ValidationRule {
            private string $message;

            public function __construct(string $message)
            {
                $this->message = $message;
            }

            public function validate($value): bool
            {
                return $value !== null && $value !== '' && $value !== [];
            }

            public function getMessage(): string
            {
                return $this->message;
            }
        };
    }

    public static function minLength(int $min, ?string $message = null): ValidationRule
    {
        $message = $message ?? "Minimum length is {$min} characters";
        return new class($min, $message) implements ValidationRule {
            private int $min;
            private string $message;

            public function __construct(int $min, string $message)
            {
                $this->min = $min;
                $this->message = $message;
            }

            public function validate($value): bool
            {
                return is_string($value) && strlen($value) >= $this->min;
            }

            public function getMessage(): string
            {
                return $this->message;
            }
        };
    }

    public static function maxLength(int $max, ?string $message = null): ValidationRule
    {
        $message = $message ?? "Maximum length is {$max} characters";
        return new class($max, $message) implements ValidationRule {
            private int $max;
            private string $message;

            public function __construct(int $max, string $message)
            {
                $this->max = $max;
                $this->message = $message;
            }

            public function validate($value): bool
            {
                return is_string($value) && strlen($value) <= $this->max;
            }

            public function getMessage(): string
            {
                return $this->message;
            }
        };
    }

    public static function pattern(string $regex, string $message): ValidationRule
    {
        return new class($regex, $message) implements ValidationRule {
            private string $regex;
            private string $message;

            public function __construct(string $regex, string $message)
            {
                $this->regex = $regex;
                $this->message = $message;
            }

            public function validate($value): bool
            {
                return is_string($value) && preg_match($this->regex, $value);
            }

            public function getMessage(): string
            {
                return $this->message;
            }
        };
    }

    public static function email(string $message = "Invalid email format"): ValidationRule
    {
        return new class($message) implements ValidationRule {
            private string $message;

            public function __construct(string $message)
            {
                $this->message = $message;
            }

            public function validate($value): bool
            {
                return is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
            }

            public function getMessage(): string
            {
                return $this->message;
            }
        };
    }

    public static function url(string $message = "Invalid URL format"): ValidationRule
    {
        return new class($message) implements ValidationRule {
            private string $message;

            public function __construct(string $message)
            {
                $this->message = $message;
            }

            public function validate($value): bool
            {
                return is_string($value) && filter_var($value, FILTER_VALIDATE_URL) !== false;
            }

            public function getMessage(): string
            {
                return $this->message;
            }
        };
    }

    public static function positiveNumber(string $message = "Must be a positive number"): ValidationRule
    {
        return new class($message) implements ValidationRule {
            private string $message;

            public function __construct(string $message)
            {
                $this->message = $message;
            }

            public function validate($value): bool
            {
                return is_numeric($value) && $value > 0;
            }

            public function getMessage(): string
            {
                return $this->message;
            }
        };
    }

    public static function nonNegativeNumber(string $message = "Must be a non-negative number"): ValidationRule
    {
        return new class($message) implements ValidationRule {
            private string $message;

            public function __construct(string $message)
            {
                $this->message = $message;
            }

            public function validate($value): bool
            {
                return is_numeric($value) && $value >= 0;
            }

            public function getMessage(): string
            {
                return $this->message;
            }
        };
    }

    public static function inRange(int $min, int $max, ?string $message = null): ValidationRule
    {
        $message = $message ?? "Value must be between {$min} and {$max}";
        return new class($min, $max, $message) implements ValidationRule {
            private int $min;
            private int $max;
            private string $message;

            public function __construct(int $min, int $max, string $message)
            {
                $this->min = $min;
                $this->max = $max;
                $this->message = $message;
            }

            public function validate($value): bool
            {
                return is_numeric($value) && $value >= $this->min && $value <= $this->max;
            }

            public function getMessage(): string
            {
                return $this->message;
            }
        };
    }

    public static function oneOf(array $allowedValues, ?string $message = null): ValidationRule
    {
        $message = $message ?? "Value must be one of: " . implode(', ', $allowedValues);
        return new class($allowedValues, $message) implements ValidationRule {
            private array $allowedValues;
            private string $message;

            public function __construct(array $allowedValues, string $message)
            {
                $this->allowedValues = $allowedValues;
                $this->message = $message;
            }

            public function validate($value): bool
            {
                return in_array($value, $this->allowedValues, true);
            }

            public function getMessage(): string
            {
                return $this->message;
            }
        };
    }
}
