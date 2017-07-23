<?php

return [
    'app' => [
        'name' => env('APP_NAME', 'Trellis'),
        'email' => env('ADMIN_EMAIL', 'help@trellis.dev'),
    ],

    'demo' => [
        'user' => [
            'email' => env('DEMO_USER_EMAIL', 'admin@trellis.com'),
            'password' => env('DEMO_USER_PASSWORD', 'secret'),
        ],
    ],
];