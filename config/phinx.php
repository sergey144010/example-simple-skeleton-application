<?php

$appConf = new \App\Config(__DIR__ . DIRECTORY_SEPARATOR . 'app.php');

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/../db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/../db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'production_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => $appConf->get()['app']['db']['host'],
            'name' => $appConf->get()['app']['db']['database'],
            'user' => $appConf->get()['app']['db']['user'],
            'pass' => $appConf->get()['app']['db']['pass'],
            'port' => $appConf->get()['app']['db']['port'],
            'charset' => 'utf8',
        ],
        'testing' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'testing_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
