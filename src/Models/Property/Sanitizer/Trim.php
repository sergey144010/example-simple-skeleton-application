<?php

namespace App\Models\Property\Sanitizer;

class Trim implements SanitizerInterface
{
    public function __construct(private ?SanitizerInterface $sanitizer = null)
    {
    }

    private function trim(mixed $value): mixed
    {
        return trim($value);
    }

    public function sanitize(mixed $value): mixed
    {
        if (isset($this->sanitizer)) {
            return $this->sanitizer->sanitize(
                $this->trim($value)
            );
        }

        return $this->trim($value);
    }
}
