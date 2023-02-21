<?php

namespace App\Models\Validators;

interface ValidatorInterface
{
    public function validateProperty(string $propertyName, mixed $propertyValue): void;
}
