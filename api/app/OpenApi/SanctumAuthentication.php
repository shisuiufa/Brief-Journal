<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Authentication',
    description: 'Authentication and Laravel Sanctum session endpoints'
)]
class SanctumAuthentication
{
    #[OA\Get(
        path: '/sanctum/csrf-cookie',
        description: 'Call this endpoint before /api/auth/login when authenticating a stateful SPA client with Sanctum.',
        summary: 'Initialize Sanctum CSRF protection',
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 204,
                description: 'CSRF cookie initialized'
            ),
        ]
    )]
    public function csrfCookie(): void {}

    #[OA\Post(
        path: '/api/auth/login',
        description: 'Authenticates the user with email and password and starts a stateful Sanctum session.',
        summary: 'Authenticate with Sanctum session',
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
                        new OA\Property(property: 'data', ref: '#/components/schemas/UserResource'),
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
        path: '/api/auth/logout',
        description: 'Ends the current authenticated session. For SPA requests, send the Sanctum session cookie and X-XSRF-TOKEN header.',
        summary: 'Logout the authenticated user',
        security: [['sanctumBearer' => []], ['sanctumCookie' => [], 'xsrfToken' => []]],
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
            new OA\Response(
                response: 419,
                description: 'CSRF token mismatch',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'CSRF token mismatch.'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function logout(): void {}
}
