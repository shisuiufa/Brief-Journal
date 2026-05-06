<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Admin\Profile\UpdateProfileActionInterface;
use App\Contracts\Admin\Profile\UpdateProfilePasswordActionInterface;
use App\Data\Admin\Profile\ProfileData;
use App\Data\Admin\Profile\ProfilePasswordData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\ProfilePasswordRequest;
use App\Http\Requests\Admin\Profile\ProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function update(ProfileRequest $request, UpdateProfileActionInterface $action): UserResource
    {
        $validated = $request->validated();

        $user = $action->execute(
            $request->user(),
            new ProfileData(
                name: $validated['name'],
                email: $validated['email'],
            )
        );

        return UserResource::make($user->load('roles'));
    }

    public function password(ProfilePasswordRequest $request, UpdateProfilePasswordActionInterface $action): JsonResponse
    {
        $validated = $request->validated();

        $action->execute(
            $request->user(),
            new ProfilePasswordData(
                currentPassword: $validated['current_password'],
                password: $validated['password'],
            ),
        );

        return response()->json([
            'message' => 'Password updated successfully.',
        ]);
    }
}
