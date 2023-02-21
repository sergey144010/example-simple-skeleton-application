<?php

namespace App\Models\Property\Sanitizer;

class NothingSanitizer implements SanitizerInterface
{
    public function sanitize(mixed $value): mixed
    {
        return $value;
    }
}
