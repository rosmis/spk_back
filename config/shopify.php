<?php

use Shopify\Auth\FileSessionStorage;

return [
    'config' => [
        'apiKey' => env('SHOPIFY_API_KEY', ''),
        'apiSecretKey' => env('SHOPIFY_API_SECRET', ''),
        'scopes' => env('SHOPIFY_API_SCOPES', ''),
        'hostName' => env('SHOPIFY_API_HOST', ''),
        'sessionStorage' => new FileSessionStorage('/tmp/php_sessions'),
        'apiVersion' => env('SHOPIFY_API_VERSION', '2024-04'),
        'isEmbeddedApp' => env('SHOPIFY_API_IS_EMBEDDED_APP', false),
        'isPrivateApp' => env('SHOPIFY_API_IS_PRIVATE_APP', false),
    ],
    'webhookSecret' => env('SHOPIFY_API_WEBHOOK_SECRET', ''),
];
