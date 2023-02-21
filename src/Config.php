<?php

namespace App;

class Config
{
    private array $config = [];

    public function __construct(string $path)
    {
        $include = include $path;
        if ($include === false) {
            throw new \RuntimeException('Path not included to Config. Input path: ' . $path);
        }

        $this->config = $include;
    }

    public function get(): array
    {
        return $this->config;
    }
}
