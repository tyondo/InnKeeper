<?php
return [
    'innkeeper-main-server' => env('INNKEEPER_MAIN_DB_SERVER','mysql'),
    'tenant-db-server' => env('INNKEEPER_TENANT_DB_SERVER','mysql'),
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
        'path' => null,
        'commands' => [
            'db:wipe',
            'migrate:fresh',
            'db:seed',
        ]
    ]
];
