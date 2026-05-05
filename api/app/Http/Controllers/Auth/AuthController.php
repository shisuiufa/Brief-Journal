<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Auth\AuthUserActionInterface;
use App\Contracts\Auth\LogoutUserActionInterface;
use App\Contracts\Auth\RefreshTokenActionInterface;
use App\Data\Auth\AuthData;
use App\Enums\Auth\AuthDriverEnum;
use App\Http\Controllers\Controller;
use App\Http\Cookies\RefreshTokenCookie;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request, AuthUserActionInterface $authenticateUser, RefreshTokenCookie $refreshTokenCookie): JsonResponse
    {
        $result = $authenticateUser(new AuthData(
            driver: AuthDriverEnum::Password,
            email: $request->validated('email'),
            password: $request->validated('password')
        ));

        return response()->json([
            'data' => [
                'user' => new UserResource($result->user),
                'token' => new TokenResource($result),
            ],
        ])->cookie($refreshTokenCookie->make($result->refreshToken));
    }

    public function refresh(
        Request $request,
        RefreshTokenActionInterface $refreshTokenAction,
        RefreshTokenCookie $refreshTokenCookie,
    ): JsonResponse {
        $result = $refreshTokenAction->execute(
            $request->cookie($refreshTokenCookie->name())
        );

        return response()
            ->json([
                'data' => [
                    'token' => new TokenResource($result),
                ],
            ])
            ->cookie($refreshTokenCookie->make($result->refreshToken));
    }

    public function logout(
        LogoutUserActionInterface $logoutAction,
        RefreshTokenCookie $refreshTokenCookie,
    ): JsonResponse {
        $logoutAction->execute();

        return response()->json([
            'message' => 'Logged out successfully.',
        ])->cookie($refreshTokenCookie->forget());
    }
}
