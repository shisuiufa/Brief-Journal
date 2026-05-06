<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Admin Users',
    description: 'Administrative user management endpoints'
)]
#[OA\Schema(
    schema: 'AdminUserStoreRequest',
    required: ['name', 'email', 'password', 'password_confirmation', 'role'],
    properties: [
        new OA\Property(property: 'name', type: 'string', maxLength: 255, minLength: 3, example: 'Jane Doe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', maxLength: 255, example: 'jane@example.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', minLength: 8, example: 'password123'),
        new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', minLength: 8, example: 'password123'),
        new OA\Property(property: 'role', type: 'string', enum: ['super-admin', 'admin', 'editor', 'user'], example: 'editor'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'AdminUserUpdateRequest',
    required: ['name', 'email'],
    properties: [
        new OA\Property(property: 'name', type: 'string', maxLength: 255, minLength: 3, example: 'Jane Doe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', maxLength: 255, example: 'jane@example.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', minLength: 8, nullable: true, example: 'new-password123'),
        new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', minLength: 8, nullable: true, example: 'new-password123'),
        new OA\Property(property: 'role', type: 'string', enum: ['super-admin', 'admin', 'editor', 'user'], nullable: true, example: 'editor'),
    ],
    type: 'object'
)]
class AdminUsersDocumentation
{
    #[OA\Get(
        path: '/api/admin/users',
        summary: 'Get users list',
        security: [['passportBearer' => []]],
        tags: ['Admin Users'],
        parameters: [
            new OA\Parameter(
                name: 'search',
                description: 'Search users by name or email',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', maxLength: 255)
            ),
            new OA\Parameter(
                name: 'role',
                description: 'Filter users by role',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', enum: ['super-admin', 'admin', 'editor', 'user'])
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Users list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/UserResource')
                        ),
                        new OA\Property(property: 'links', type: 'object'),
                        new OA\Property(property: 'meta', type: 'object'),
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
                response: 403,
                description: 'Forbidden',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'This action is unauthorized.'
                        ),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/admin/users',
        summary: 'Create a user',
        security: [['passportBearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/AdminUserStoreRequest')
        ),
        tags: ['Admin Users'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'User created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'User created successfully.'),
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
                response: 403,
                description: 'Forbidden',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'This action is unauthorized.'
                        ),
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
    public function store(): void {}

    #[OA\Get(
        path: '/api/admin/users/{user}',
        summary: 'Show a user',
        security: [['passportBearer' => []]],
        tags: ['Admin Users'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                description: 'User ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User details',
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
                response: 403,
                description: 'Forbidden',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'This action is unauthorized.'
                        ),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'User not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model.'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function show(): void {}

    #[OA\Put(
        path: '/api/admin/users/{user}',
        summary: 'Update a user',
        security: [['passportBearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/AdminUserUpdateRequest')
        ),
        tags: ['Admin Users'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                description: 'User ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'User updated successfully.'),
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
                response: 403,
                description: 'Forbidden',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'This action is unauthorized.'
                        ),
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

    #[OA\Delete(
        path: '/api/admin/users/{user}',
        summary: 'Delete a user',
        security: [['passportBearer' => []]],
        tags: ['Admin Users'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                description: 'User ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'User deleted successfully.'),
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
                response: 403,
                description: 'Forbidden',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'This action is unauthorized.'
                        ),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function destroy(): void {}
}
