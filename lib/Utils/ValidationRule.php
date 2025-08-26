<?php

namespace OpenAPI\Client\Utils;

interface ValidationRule
{
    public function validate($value): bool;
    public function getMessage(): string;
}
