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
    securityScheme: 'passportBearer',
    type: 'http',
    description: 'Use a Laravel Passport bearer token in the Authorization header.',
    bearerFormat: 'JWT',
    scheme: 'bearer'
)]
#[OA\Schema(
    schema: 'PassportToken',
    required: ['access_token', 'token_type', 'expires_in'],
    properties: [
        new OA\Property(property: 'access_token', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...'),
        new OA\Property(property: 'refresh_token', type: 'string', nullable: true, example: null),
        new OA\Property(property: 'token_type', type: 'string', example: 'Bearer'),
        new OA\Property(property: 'expires_in', type: 'integer', example: 31536000),
    ],
    type: 'object'
)]
class OpenApi {}
