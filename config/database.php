<?php
return [
    'default' => env('DB_CONNECTION', 'couchbase'),
    'connections' => [
        'couchbase' => [
            'driver' => 'couchbase',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', 8091),
            'bucket' => env('DB_DATABASE'),
            'user' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'n1ql_hosts' => [
                'http://' . env('DB_HOST', 'localhost') . ':8093',
            ],
        ],
    ],
];
