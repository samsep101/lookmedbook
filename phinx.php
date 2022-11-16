<?php
require __DIR__ . '/application/config/db.cfg.php';

return [
    'paths' => [
        'migrations' => __DIR__ . '/db/migrations',
    ],
    'environments' =>
        [
            'main' => [
                'name' => DB_NAME,
                'adapter' => 'mysql',
                'host' => DB_HOST,
                'user' => DB_USER,
                'pass' => DB_PASSWORD,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ]
        ]
];