<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Configuración de CORS para permitir que el frontend (Laravel + React)
    | local se comunique con la API alojada en Railway.
    |
    */

    'paths' => [
        'api/*',
        'api/v1/*',
        'sanctum/csrf-cookie',
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        env('FRONTEND_URL', 'http://127.0.0.1:8000'), // Laravel local
        'http://localhost:8000',                      // alternativa localhost
        'http://localhost:3000',                      // si usas Vite o React puro
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
