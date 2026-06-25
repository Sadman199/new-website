<?php

return [
    'enabled' => env('RESPONSE_CACHE_ENABLED', true),

    // Cache driver (use 'file' for cPanel; 'redis' if your hosting supports it)
    'cache_store' => env('RESPONSE_CACHE_DRIVER', 'file'),

    // How long to cache pages (in seconds)
    'cache_lifetime_in_seconds' => env('RESPONSE_CACHE_LIFETIME', 3600), // 1 hour

    // Skip caching for authenticated users (recommended)
    'cache_authenticated_users' => false,
];
