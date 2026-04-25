<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Admin\User\CreateUserActionInterface;
use App\Contracts\Admin\User\DeleteUserActionInterface;
use App\Contracts\Admin\User\UpdateUserActionInterface;
use App\Data\Admin\User\CreateUserData;
use App\Data\Admin\User\UpdateUserData;
use App\Enums\Access\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class UserController extends Controller
{
    #[Authorize('viewAny', User::class)]
    public function index(Request $request): ResourceCollection
    {
        $users = User::query()
            ->search($request->string('search')->toString())
            ->with('roles')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return UserResource::collection($users);
    }

    #[Authorize('create', User::class)]
    public function store(StoreUserRequest $request, CreateUserActionInterface $createUserAction): JsonResponse
    {
        $validated = $request->validated();
        $role = RoleEnum::from($validated['role']);

        $this->authorize('createWithRole', [User::class, $role]);

        $createUserAction->execute(new CreateUserData(
            name: $validated['name'],
            email: $validated['email'],
            password: $validated['password'],
            role: $role,
        ));

        return response()->json([
            'message' => 'User created successfully.',
        ], 201);
    }

    #[Authorize('view', 'user')]
    public function show(User $user): UserResource
    {
        $user->load('roles');

        return new UserResource($user);
    }

    #[Authorize('update', 'user')]
    public function update(
        UpdateUserRequest $request,
        User $user,
        UpdateUserActionInterface $updateUserAction
    ): JsonResponse {
        $validated = $request->validated();

        $role = isset($validated['role']) ? RoleEnum::from($validated['role']) : null;

        if ($role !== null) {
            $this->authorize('changeRole', [$user, $role]);
        }

        $updateUserAction->execute(
            $user,
            new UpdateUserData(
                name: $validated['name'],
                email: $validated['email'],
                role: $role,
            )
        );

        return response()->json([
            'message' => 'User updated successfully.',
        ]);
    }

    #[Authorize('delete', 'user')]
    public function destroy(User $user, DeleteUserActionInterface $deleteUserAction): JsonResponse
    {
        $deleteUserAction->execute($user);

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
