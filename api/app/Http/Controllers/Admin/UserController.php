<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Admin\User\CreateUserActionInterface;
use App\Contracts\Admin\User\DeleteUserActionInterface;
use App\Contracts\Admin\User\UpdateUserActionInterface;
use App\Data\Admin\CreateUserData;
use App\Data\Admin\UpdateUserData;
use App\Enums\Access\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    #[OA\Get(
        path: '/api/admin/users',
        summary: 'Get users list',
        security: [['sanctumBearer' => []], ['sanctumCookie' => []]],
        tags: ['Admin Users'],
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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection
    {
        $this->authorize('viewAny', User::class);

        $users = User::search($request->input('search'))
            ->with('roles')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return UserResource::collection($users);
    }

    #[OA\Post(
        path: '/api/admin/users',
        summary: 'Create a user',
        security: [['sanctumBearer' => []], ['sanctumCookie' => [], 'xsrfToken' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password', 'role'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Jane Doe', maxLength: 255, minLength: 3),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jane@example.com', maxLength: 255),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123', minLength: 8),
                    new OA\Property(
                        property: 'role',
                        type: 'string',
                        example: 'editor',
                        enum: ['super-admin', 'admin', 'editor', 'user']
                    ),
                ],
                type: 'object'
            )
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
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, CreateUserActionInterface $createUserAction): JsonResponse
    {
        $role = RoleEnum::from($request->validated('role'));

        $this->authorize('createWithRole', [User::class, $role]);

        $createUserAction->execute(new CreateUserData(
            name: $request->validated('name'),
            email: $request->validated('email'),
            password: $request->validated('password'),
            role: $role,
        ));

        return response()->json([
            'message' => 'User created successfully.',
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/users/{user}',
        summary: 'Show a user',
        security: [['sanctumBearer' => []], ['sanctumCookie' => []]],
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
    /**
     * Display the specified resource.
     */
    public function show(User $user): UserResource
    {
        $this->authorize('view', $user);

        return new UserResource($user);
    }

    #[OA\Put(
        path: '/api/admin/users/{user}',
        summary: 'Update a user',
        security: [['sanctumBearer' => []], ['sanctumCookie' => [], 'xsrfToken' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Jane Doe', maxLength: 255, minLength: 3),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jane@example.com', maxLength: 255),
                    new OA\Property(
                        property: 'role',
                        type: 'string',
                        example: 'editor',
                        nullable: true,
                        enum: ['super-admin', 'admin', 'editor', 'user']
                    ),
                ],
                type: 'object'
            )
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
    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateUserRequest $request,
        User $user,
        UpdateUserActionInterface $updateUserAction
    ): JsonResponse {
        $role = RoleEnum::from($request->validated('role'));

        $this->authorize('update', [$user, $role]);

        $updateUserAction->execute(
            $user,
            new UpdateUserData(
                name: $request->validated('name'),
                email: $request->validated('email'),
                role: RoleEnum::from($request->validated('role')),
            )
        );

        return response()->json([
            'message' => 'User updated successfully.',
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/users/{user}',
        summary: 'Delete a user',
        security: [['sanctumBearer' => []], ['sanctumCookie' => [], 'xsrfToken' => []]],
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
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, DeleteUserActionInterface $deleteUserAction): JsonResponse
    {
        $this->authorize('delete', $user);

        $deleteUserAction->execute($user);

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
