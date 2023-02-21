<?php

namespace App\Helpers;

class Env
{
    public static function get(string $env): string|null
    {
        $e = getenv($env);
        if (! is_string($e)) {
            return null;
        }
        return $e;
    }
}
