<?php

namespace App;

class PersistOptions
{
    readonly public string $table;

    public function __construct(string $table)
    {
        $this->table = strtolower($table);
    }
}
