<?php

use App\Helpers\Env;

return [
    'app' => [
        'db' => [
            'driver' => 'mysql',
            'database' => Env::get('MYSQL_DATABASE') ?? '',
            'host' => Env::get('MYSQL_HOST') ?? '',
            'user' => Env::get('MYSQL_USER') ?? '',
            'pass' => Env::get('MYSQL_PASSWORD') ?? '',
            'port' => Env::get('MYSQL_PORT') ?? '',
        ]
    ]
];
