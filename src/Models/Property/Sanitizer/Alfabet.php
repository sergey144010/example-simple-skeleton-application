<?php

namespace App\Models\Property\Sanitizer;

class Alfabet implements SanitizerInterface
{
    public function __construct(private ?SanitizerInterface $sanitizer = null)
    {
    }

    private function alfabet(mixed $value): mixed
    {
        preg_match('/[a-zA-Z]+/', $value, $matches);
        return $matches[0] ?? '';
    }

    public function sanitize(mixed $value): mixed
    {
        if (isset($this->sanitizer)) {
            return $this->sanitizer->sanitize(
                $this->alfabet($value)
            );
        }

        return $this->alfabet($value);
    }
}
