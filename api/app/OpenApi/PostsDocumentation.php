<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Posts',
    description: 'Public post endpoints'
)]
#[OA\Tag(
    name: 'Admin Posts',
    description: 'Administrative post management endpoints'
)]
#[OA\Schema(
    schema: 'PostStoreRequest',
    required: ['title', 'slug', 'image', 'content', 'status'],
    properties: [
        new OA\Property(property: 'title', type: 'string', maxLength: 255, example: 'Building APIs with Laravel Passport'),
        new OA\Property(property: 'slug', type: 'string', maxLength: 255, example: 'building-apis-with-laravel-passport'),
        new OA\Property(property: 'image', type: 'string', format: 'binary'),
        new OA\Property(property: 'excerpt', type: 'string', maxLength: 1000, nullable: true, example: 'Short post summary.'),
        new OA\Property(property: 'content', type: 'string', example: 'Post body content.'),
        new OA\Property(property: 'status', type: 'string', enum: ['draft', 'published'], example: 'published'),
        new OA\Property(
            property: 'category_ids',
            type: 'array',
            items: new OA\Items(type: 'integer'),
            example: [1, 2]
        ),
        new OA\Property(
            property: 'tag_ids',
            type: 'array',
            items: new OA\Items(type: 'integer'),
            example: [1, 2]
        ),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'PostUpdateRequest',
    required: ['title', 'slug', 'content', 'status'],
    properties: [
        new OA\Property(property: 'title', type: 'string', maxLength: 255, example: 'Building APIs with Laravel Passport'),
        new OA\Property(property: 'slug', type: 'string', maxLength: 255, example: 'building-apis-with-laravel-passport'),
        new OA\Property(property: 'image', type: 'string', format: 'binary', nullable: true),
        new OA\Property(property: 'excerpt', type: 'string', maxLength: 1000, nullable: true, example: 'Short post summary.'),
        new OA\Property(property: 'content', type: 'string', example: 'Post body content.'),
        new OA\Property(property: 'status', type: 'string', enum: ['draft', 'published'], example: 'published'),
        new OA\Property(
            property: 'category_ids',
            type: 'array',
            items: new OA\Items(type: 'integer'),
            example: [1, 2]
        ),
        new OA\Property(
            property: 'tag_ids',
            type: 'array',
            items: new OA\Items(type: 'integer'),
            example: [1, 2]
        ),
    ],
    type: 'object'
)]
class PostsDocumentation
{
    #[OA\Get(
        path: '/api/posts',
        summary: 'Get published posts',
        tags: ['Posts'],
        parameters: [
            new OA\Parameter(
                name: 'search',
                description: 'Search posts by title or excerpt',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', maxLength: 255)
            ),
            new OA\Parameter(
                name: 'category',
                description: 'Filter posts by category slug',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', maxLength: 255)
            ),
            new OA\Parameter(
                name: 'tag',
                description: 'Filter posts by tag slug',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', maxLength: 255)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Published posts list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/PostResource')
                        ),
                        new OA\Property(property: 'links', type: 'object'),
                        new OA\Property(property: 'meta', type: 'object'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function publicIndex(): void {}

    #[OA\Get(
        path: '/api/posts/{slug}',
        summary: 'Show a published post',
        tags: ['Posts'],
        parameters: [
            new OA\Parameter(
                name: 'slug',
                description: 'Post slug',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', maxLength: 255)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Published post details',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/PostResource'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Post not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model.'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function publicShow(): void {}

    #[OA\Get(
        path: '/api/admin/posts',
        summary: 'Get posts list',
        security: [['passportBearer' => []]],
        tags: ['Admin Posts'],
        parameters: [
            new OA\Parameter(
                name: 'search',
                description: 'Search posts by title or excerpt',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', maxLength: 255)
            ),
            new OA\Parameter(
                name: 'status',
                description: 'Filter posts by status',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', enum: ['draft', 'published'])
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Posts list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/PostResource')
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
                        new OA\Property(property: 'message', type: 'string', example: 'This action is unauthorized.'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function adminIndex(): void {}

    #[OA\Post(
        path: '/api/admin/posts',
        summary: 'Create a post',
        security: [['passportBearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(ref: '#/components/schemas/PostStoreRequest')
            )
        ),
        tags: ['Admin Posts'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Post created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Post created successfully.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/PostResource'),
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
                        new OA\Property(property: 'message', type: 'string', example: 'This action is unauthorized.'),
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
    public function adminStore(): void {}

    #[OA\Get(
        path: '/api/admin/posts/{post}',
        summary: 'Show a post',
        security: [['passportBearer' => []]],
        tags: ['Admin Posts'],
        parameters: [
            new OA\Parameter(
                name: 'post',
                description: 'Post ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Post details',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/PostResource'),
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
                        new OA\Property(property: 'message', type: 'string', example: 'This action is unauthorized.'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Post not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model.'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function adminShow(): void {}

    #[OA\Put(
        path: '/api/admin/posts/{post}',
        summary: 'Update a post',
        security: [['passportBearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(ref: '#/components/schemas/PostUpdateRequest')
            )
        ),
        tags: ['Admin Posts'],
        parameters: [
            new OA\Parameter(
                name: 'post',
                description: 'Post ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Post updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Post updated successfully.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/PostResource'),
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
                        new OA\Property(property: 'message', type: 'string', example: 'This action is unauthorized.'),
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
    public function adminUpdate(): void {}

    #[OA\Delete(
        path: '/api/admin/posts/{post}',
        summary: 'Delete a post',
        security: [['passportBearer' => []]],
        tags: ['Admin Posts'],
        parameters: [
            new OA\Parameter(
                name: 'post',
                description: 'Post ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Post deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Post deleted successfully.'),
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
                        new OA\Property(property: 'message', type: 'string', example: 'This action is unauthorized.'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function adminDestroy(): void {}
}
