<?php

namespace App\Models\Validators;

abstract class AbstractValidator implements ValidatorInterface
{
    public function validateProperty(string $propertyName, mixed $propertyValue): void
    {
        if (method_exists($this, $propertyName)) {
            $this->$propertyName($propertyValue);
        }
    }
}
