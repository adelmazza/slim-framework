<?php

return [
    'settings' => [

        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => false,
        'addContentLengthHeader' => false,

        'debug' => true,

        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'databasename',
            'username' => 'root',
            'password' => 'root',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],

        'session' => [
            // cookie settings
            'name'           => 'adm_session',
            'lifetime'       => 20,
            'path'           => '/',
            'domain'         => null,
            'secure'         => false,
            'httponly'       => true,

            // Path where session files are stored, PHP's default path will be used if set null
            'save_path'      => __DIR__ . '/../temp/sessions',
            // Session cache limiter
            'cache_limiter'  => 'nocache',
            // Extend session lifetime after each user activity
            'autorefresh'    => false,
        ],

        'mailer' => [
            'smtp' => 'smtp.gmail.com',
            'port' => 587,
            'security' => 'tls',
            'username' => '',
            'password' => '',
        ],

        'logger' => [
            'name' => 'fwk-log',
            'filename' => 'logger.log',
            'maxfiles' => 10,
        ],

        'validation' => [
            'lang' => 'it',
        ]
    ],
];