<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'User',
    description: 'Authenticated user endpoints'
)]
class UserDocumentation
{
    #[OA\Get(
        path: '/api/user',
        summary: 'Get authenticated user',
        security: [['passportBearer' => []]],
        tags: ['User'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Authenticated user details',
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
        ]
    )]
    public function show(): void {}
}
