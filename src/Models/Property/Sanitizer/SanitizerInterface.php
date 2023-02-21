<?php

namespace App\Models\Property\Sanitizer;

interface SanitizerInterface
{
    public function sanitize(mixed $value): mixed;
}
