<?php

$app_url = env('REACT_APP_URL');

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed-methods' => ['*'],

    'allowed-origins' => [$app_url],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];