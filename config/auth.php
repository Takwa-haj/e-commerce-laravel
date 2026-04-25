<?php

return [

  // config/auth.php
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Remove or fix the auth0 guard configuration
        // 'auth0' => [
        //     'driver' => 'auth0',
        //     'provider' => 'auth0',
        // ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // Remove or comment out the auth0 provider for now
        // 'auth0' => [
        //     'driver' => 'auth0',
        //     'repository' => Auth0\Login\Auth0UserRepository::class,
        // ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
