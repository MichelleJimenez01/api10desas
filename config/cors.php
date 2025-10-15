<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    // Aplica CORS solo a rutas API (más seguro que usar '*')
    'paths' => ['api/*', 'api/v1/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        env('FRONTEND_URL', 'http://127.0.0.1:8000'), // frontend Laravel + React
        'http://localhost:8000',                      // alternativa
        'http://localhost:3000',                      // por si en algún momento lo sirves en React puro
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
