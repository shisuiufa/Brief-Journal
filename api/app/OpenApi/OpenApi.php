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
#[OA\Schema(
    schema: 'UserResource',
    required: ['id', 'name', 'email', 'roles'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Jane Doe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jane@example.com'),
        new OA\Property(
            property: 'roles',
            type: 'array',
            items: new OA\Items(type: 'string', example: 'editor')
        ),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'PostResource',
    required: ['id', 'title', 'slug', 'excerpt', 'content', 'image_url', 'status', 'published_at', 'views_count'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Building APIs with Laravel Passport'),
        new OA\Property(property: 'slug', type: 'string', example: 'building-apis-with-laravel-passport'),
        new OA\Property(property: 'excerpt', type: 'string', nullable: true, example: 'Short post summary.'),
        new OA\Property(property: 'content', type: 'string', example: 'Post body content.'),
        new OA\Property(property: 'image_url', type: 'string', example: 'http://localhost/storage/posts/example.jpg'),
        new OA\Property(property: 'status', type: 'string', enum: ['draft', 'published'], example: 'published'),
        new OA\Property(property: 'published_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'author', ref: '#/components/schemas/UserResource'),
        new OA\Property(
            property: 'categories',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/CategoryResource')
        ),
        new OA\Property(
            property: 'tags',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/TagResource')
        ),
        new OA\Property(property: 'views_count', type: 'integer', example: 42),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'CategoryResource',
    required: ['id', 'name', 'slug'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Laravel'),
        new OA\Property(property: 'slug', type: 'string', example: 'laravel'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'TagResource',
    required: ['id', 'name', 'slug'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Passport'),
        new OA\Property(property: 'slug', type: 'string', example: 'passport'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ],
    type: 'object'
)]
class OpenApi {}
