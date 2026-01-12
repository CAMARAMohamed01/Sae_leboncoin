<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users', 
        ],
        'pro' => [
            'driver' => 'session',
            'provider' => 'professionnels', 
        ],
    ],


    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\CompteUtilisateur::class,
        ],

        'professionnels' => [ 
            'driver' => 'eloquent',
            'model' => App\Models\Professionnel::class, 
        ],
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
