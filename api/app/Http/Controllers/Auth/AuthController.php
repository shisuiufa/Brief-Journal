<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Auth\AuthUserActionInterface;
use App\Contracts\Auth\LogoutUserActionInterface;
use App\Data\Auth\AuthData;
use App\Enums\Auth\AuthDriverEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(LoginRequest $request, AuthUserActionInterface $authenticateUser): UserResource
    {
        $user = $authenticateUser(new AuthData(
            driver: AuthDriverEnum::Password,
            email: $request->validated('email'),
            password: $request->validated('password')
        ));

        return new UserResource($user);
    }

    public function logout(LogoutUserActionInterface $logoutAction): JsonResponse
    {
        $logoutAction->execute();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }
}
