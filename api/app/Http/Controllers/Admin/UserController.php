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

class UserController extends Controller
{
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, CreateUserActionInterface $createUserAction): JsonResponse
    {
        $validated = $request->validated();
        $role = RoleEnum::from($validated['role']);

        $this->authorize('create', User::class);
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

    /**
     * Display the specified resource.
     */
    public function show(User $user): UserResource
    {
        $this->authorize('view', $user);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateUserRequest $request,
        User $user,
        UpdateUserActionInterface $updateUserAction
    ): JsonResponse {
        $validated = $request->validated();

        $role = isset($validated['role']) ? RoleEnum::from($validated['role']) : null;

        $this->authorize('update', $user);

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
