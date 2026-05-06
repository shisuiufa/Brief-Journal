<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Admin Categories',
    description: 'Administrative category management endpoints'
)]
#[OA\Tag(
    name: 'Admin Tags',
    description: 'Administrative tag management endpoints'
)]
#[OA\Schema(
    schema: 'CategoryRequest',
    required: ['name', 'slug'],
    properties: [
        new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Laravel'),
        new OA\Property(property: 'slug', type: 'string', maxLength: 255, example: 'laravel'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'TagRequest',
    required: ['name', 'slug'],
    properties: [
        new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Passport'),
        new OA\Property(property: 'slug', type: 'string', maxLength: 255, example: 'passport'),
    ],
    type: 'object'
)]
class AdminTaxonomyDocumentation
{
    #[OA\Get(
        path: '/api/admin/categories',
        summary: 'Get categories list',
        security: [['passportBearer' => []]],
        tags: ['Admin Categories'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Categories list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/CategoryResource')
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
    public function categoryIndex(): void {}

    #[OA\Post(
        path: '/api/admin/categories',
        summary: 'Create a category',
        security: [['passportBearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/CategoryRequest')
        ),
        tags: ['Admin Categories'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Category created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Category created successfully.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/CategoryResource'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function categoryStore(): void {}

    #[OA\Get(
        path: '/api/admin/categories/{category}',
        summary: 'Show a category',
        security: [['passportBearer' => []]],
        tags: ['Admin Categories'],
        parameters: [
            new OA\Parameter(
                name: 'category',
                description: 'Category ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Category details',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/CategoryResource'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Category not found'),
        ]
    )]
    public function categoryShow(): void {}

    #[OA\Put(
        path: '/api/admin/categories/{category}',
        summary: 'Update a category',
        security: [['passportBearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/CategoryRequest')
        ),
        tags: ['Admin Categories'],
        parameters: [
            new OA\Parameter(
                name: 'category',
                description: 'Category ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Category updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Category updated successfully.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/CategoryResource'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function categoryUpdate(): void {}

    #[OA\Delete(
        path: '/api/admin/categories/{category}',
        summary: 'Delete a category',
        security: [['passportBearer' => []]],
        tags: ['Admin Categories'],
        parameters: [
            new OA\Parameter(
                name: 'category',
                description: 'Category ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Category deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Category deleted successfully.'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    public function categoryDestroy(): void {}

    #[OA\Get(
        path: '/api/admin/tags',
        summary: 'Get tags list',
        security: [['passportBearer' => []]],
        tags: ['Admin Tags'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tags list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/TagResource')
                        ),
                        new OA\Property(property: 'links', type: 'object'),
                        new OA\Property(property: 'meta', type: 'object'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    public function tagIndex(): void {}

    #[OA\Post(
        path: '/api/admin/tags',
        summary: 'Create a tag',
        security: [['passportBearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/TagRequest')
        ),
        tags: ['Admin Tags'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Tag created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Tag created successfully.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/TagResource'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function tagStore(): void {}

    #[OA\Get(
        path: '/api/admin/tags/{tag}',
        summary: 'Show a tag',
        security: [['passportBearer' => []]],
        tags: ['Admin Tags'],
        parameters: [
            new OA\Parameter(
                name: 'tag',
                description: 'Tag ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tag details',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/TagResource'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Tag not found'),
        ]
    )]
    public function tagShow(): void {}

    #[OA\Put(
        path: '/api/admin/tags/{tag}',
        summary: 'Update a tag',
        security: [['passportBearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/TagRequest')
        ),
        tags: ['Admin Tags'],
        parameters: [
            new OA\Parameter(
                name: 'tag',
                description: 'Tag ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tag updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Tag updated successfully.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/TagResource'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function tagUpdate(): void {}

    #[OA\Delete(
        path: '/api/admin/tags/{tag}',
        summary: 'Delete a tag',
        security: [['passportBearer' => []]],
        tags: ['Admin Tags'],
        parameters: [
            new OA\Parameter(
                name: 'tag',
                description: 'Tag ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tag deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Tag deleted successfully.'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    public function tagDestroy(): void {}
}
