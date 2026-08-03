<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CORS — Cross-Origin Resource Sharing
    |--------------------------------------------------------------------------
    | The mobile app (Flutter) uses native HTTP, so CORS is only needed when
    | testing via a browser (Flutter web, Postman web, etc.).
    | For now we allow all origins; tighten this once the app goes to production
    | by replacing '*' with your app's origin domains.
    */

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Must be false when allowed_origins is '*'
    'supports_credentials' => false,
];
