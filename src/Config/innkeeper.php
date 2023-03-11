<?php
return [
    'main_domain' => env('INNKEEPER_MAIN_DOMAIN','appointments.tyondo.com'),
    'tenant_landing_page' => env('INNKEEPER_TENANT_LANDING_PAGE'),
    'innkeeper-main-server' => env('INNKEEPER_MAIN_DB_SERVER','mysql'),
    'tenant-db-server' => env('INNKEEPER_TENANT_DB_SERVER','mysql'),
    'env_config_keys' => [
        '#InnKeeper Domain' => [
            'INNKEEPER_MAIN_DOMAIN' => '',
            'INNKEEPER_TENANT_LANDING_PAGE' => '',
        ],
        '#InnKeeper DB Connections' => [
            'INNKEEPER_MAIN_DB_SERVER' => 'mysql',
            'INNKEEPER_TENANT_DB_SERVER' => 'mysql',
            'INNKEEPER_TENANT_MIGRATIONS_PATH' => '/database/migrations/',
        ],
        '#InnKeeper Main SQL Connection' => [
            'INNKEEPER_MAIN_DB_HOST' => '127.0.0.1',
            'INNKEEPER_MAIN_DB_PORT' => '3306',
            'INNKEEPER_MAIN_DB_DATABASE' => 'innkeeper_main',
            'INNKEEPER_MAIN_DB_USERNAME' => 'root',
            'INNKEEPER_MAIN_DB_PASSWORD' => 'tyondo123',
        ]
    ],
    'connections' => [
        'innkeeper' => [
            'mysql' => [
                'host' => env('INNKEEPER_MAIN_DB_HOST'),
                'port' => env('INNKEEPER_MAIN_DB_PORT'),
                'database' => env('INNKEEPER_MAIN_DB_DATABASE'),
                'username' => env('INNKEEPER_MAIN_DB_USERNAME'),
                'password' => env('INNKEEPER_MAIN_DB_PASSWORD'),
            ],
            'plesk' => [
                'web_space_id' => env('INNKEEPER_PLESK_SITE_ID'),
                'user' => env('INNKEEPER_PLESK_USER'),
                'password' => env('INNKEEPER_PLESK_PASSWORD'),
                'host' => env('INNKEEPER_PLESK_HOST')
            ],
        ],
        'tenant' => [
            'mysql' => [

            ],
        ]
    ],
    'migrations' => [
        'connection' => 'tenant', //do not modify
        'path' => env('INNKEEPER_TENANT_MIGRATIONS_PATH'),
        'commands' => [
            'db:wipe',
            'migrate:fresh',
            'db:seed',
        ]
    ],
    'execute' => [
        'commands' => [
            //'system:init'
        ]
    ]
];
