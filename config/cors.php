<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Paths that should allow CORS
    'allowed_methods' => ['*'], // Allowed HTTP methods
    'allowed_origins' => ['http://localhost:3000'], // Replace with your Next.js frontend URL
    'allowed_origins_patterns' => [], // Regex patterns for allowed origins
    'allowed_headers' => ['*'], // Allowed headers
    'exposed_headers' => [], // Headers exposed to the client
    'max_age' => 0, // Max age for preflight requests
    'supports_credentials' => false, // Whether to allow credentials (cookies, etc.)
];