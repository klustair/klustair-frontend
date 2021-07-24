<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | This option defines KlustAIR authentication.
    |
    */
    'auth' => [
        /*
        | It can be disabled with 'false' if you are handling authentication elsewhere (ingress).
        | But you should really make shure your vulnerabilities are kept private.
        */
        'enabled' => env('AUTH', false),

        'register' => env('AUTH_REGISTER', false),
        'reset' => env('AUTH_RESET', false),
        'verify' => env('AUTH_VERIFY', false),
        
    ],
    'adminUser' => env('KLUSTAIR_ADMIN_USER', 'admin'),
    'adminEmail' => env('KLUSTAIR_ADMIN_EMAIL', 'admin@admin.com'),
    'adminPassword' => env('KLUSTAIR_ADMIN_PASS'),

];