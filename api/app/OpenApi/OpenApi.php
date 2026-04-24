<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'API documentation for Laravel application',
    title: 'Laravel API Documentation'
)]
#[OA\Server(
    url: '/api',
    description: 'API Server'
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctumBearer',
    type: 'http',
    description: 'Use a Laravel Sanctum personal access token in the Authorization header.',
    bearerFormat: 'Token',
    scheme: 'bearer'
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctumCookie',
    type: 'apiKey',
    description: 'Laravel Sanctum SPA session cookie.',
    name: 'laravel_session',
    in: 'cookie'
)]
#[OA\SecurityScheme(
    securityScheme: 'xsrfToken',
    type: 'apiKey',
    description: 'CSRF token header required for stateful Sanctum SPA requests.',
    name: 'X-XSRF-TOKEN',
    in: 'header'
)]
class OpenApi {}
