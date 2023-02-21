<?php

namespace App\Models\Property;

use App\Models\Property\Sanitizer\NothingSanitizer;
use App\Models\Property\Sanitizer\SanitizerInterface;
use App\Models\Validators\ValidatorInterface;

class PropertyCreator
{
    public static function create(
        string $propertyName,
        mixed $propertyValue,
        ValidatorInterface $validator,
        SanitizerInterface $sanitizer = new NothingSanitizer(),
    ): mixed {
        $property = $sanitizer->sanitize($propertyValue);
        $validator->validateProperty($propertyName, $property);

        return $property;
    }
}
