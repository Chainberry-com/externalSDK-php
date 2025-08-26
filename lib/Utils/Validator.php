<?php

namespace OpenAPI\Client\Utils;

use OpenAPI\Client\Errors\ValidationError;

class Validator
{
    private array $rules = [];

    public function addRule(ValidationRule $rule): self
    {
        $this->rules[] = $rule;
        return $this;
    }

    public function validate($value): ValidationResult
    {
        $errors = [];

        foreach ($this->rules as $rule) {
            if (!$rule->validate($value)) {
                $errors[] = $rule->getMessage();
            }
        }

        return new ValidationResult(empty($errors), $errors);
    }

    public function validateAndThrow($value, ?string $context = null): void
    {
        $result = $this->validate($value);
        if (!$result->isValid) {
            $errorMessage = $context ? "{$context}: " . implode(', ', $result->errors) : implode(', ', $result->errors);
            throw new ValidationError($errorMessage, ['value' => $value, 'errors' => $result->errors]);
        }
    }
}
