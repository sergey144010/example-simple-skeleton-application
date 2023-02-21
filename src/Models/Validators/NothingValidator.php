<?php

namespace App\Models\Validators;

class NothingValidator implements ValidatorInterface
{
    public function validateProperty(string $propertyName, mixed $propertyValue): void
    {
    }

    public function validate(): void
    {
    }
}
