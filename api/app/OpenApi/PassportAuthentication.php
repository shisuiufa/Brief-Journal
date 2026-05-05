<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Authentication',
    description: 'Authentication endpoints protected by Laravel Passport'
)]
class PassportAuthentication
{
    #[OA\Post(
        path: '/api/auth/login',
        description: 'Authenticates the user with email and password and returns a Laravel Passport bearer token.',
        summary: 'Authenticate with Passport token',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        format: 'email',
                        example: 'admin@example.com'
                    ),
                    new OA\Property(
                        property: 'password',
                        type: 'string',
                        format: 'password',
                        example: 'password'
                    ),
                ],
                type: 'object'
            )
        ),
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Authenticated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(property: 'user', ref: '#/components/schemas/UserResource'),
                                new OA\Property(property: 'token', ref: '#/components/schemas/PassportToken'),
                            ],
                            type: 'object'
                        ),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Invalid credentials or validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 429,
                description: 'Too many login attempts',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Too Many Attempts.'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function login(): void {}

    #[OA\Post(
        path: '/api/auth/refresh',
        description: 'Issues a new Laravel Passport bearer token using the httpOnly refresh token cookie.',
        summary: 'Refresh Passport token',
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Token refreshed successfully',
                headers: [
                    new OA\Header(
                        header: 'Set-Cookie',
                        description: 'Updated httpOnly refresh_token cookie',
                        schema: new OA\Schema(type: 'string')
                    ),
                ],
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(property: 'token', ref: '#/components/schemas/PassportToken'),
                            ],
                            type: 'object'
                        ),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Missing or invalid refresh token',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 429,
                description: 'Too many refresh attempts',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Too Many Attempts.'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function refresh(): void {}

    #[OA\Post(
        path: '/api/auth/logout',
        description: 'Revokes the current Laravel Passport bearer token.',
        summary: 'Logout the authenticated user',
        security: [['passportBearer' => []]],
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Logged out successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Logged out successfully.'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function logout(): void {}
}
