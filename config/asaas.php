<?php

return [
    'timeout' => env('ASAAS_TIMEOUT_CLIENT'),
    'environment' => env('APP_ENV', 'sandbox'),
    'token' => env('ASAAS_KEY_API'),
    'url' => [
        'production' => 'https://api.asaas.com/',
        'sandbox' => 'https://sandbox.asaas.com/api/',
    ],
];
