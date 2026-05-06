<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Admin Profile',
    description: 'Authenticated administrator profile endpoints'
)]
#[OA\Schema(
    schema: 'ProfileUpdateRequest',
    required: ['name', 'email'],
    properties: [
        new OA\Property(property: 'name', type: 'string', maxLength: 255, minLength: 3, example: 'Jane Doe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', maxLength: 255, example: 'jane@example.com'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ProfilePasswordUpdateRequest',
    required: ['current_password'],
    properties: [
        new OA\Property(property: 'current_password', type: 'string', format: 'password', example: 'current-password'),
        new OA\Property(property: 'password', type: 'string', format: 'password', minLength: 8, nullable: true, example: 'new-password123'),
        new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', minLength: 8, nullable: true, example: 'new-password123'),
    ],
    type: 'object'
)]
class AdminProfileDocumentation
{
    #[OA\Patch(
        path: '/api/admin/profile',
        summary: 'Update authenticated user profile',
        security: [['passportBearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/ProfileUpdateRequest')
        ),
        tags: ['Admin Profile'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Profile updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/UserResource'),
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
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function update(): void {}

    #[OA\Patch(
        path: '/api/admin/profile/password',
        summary: 'Update authenticated user password',
        security: [['passportBearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/ProfilePasswordUpdateRequest')
        ),
        tags: ['Admin Profile'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Password updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Password updated successfully.'),
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
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function password(): void {}
}
