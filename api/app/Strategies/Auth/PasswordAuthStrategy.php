<?php

namespace App\Strategies\Auth;

use App\Contracts\Auth\AuthStrategyInterface;
use App\Data\Auth\AuthData;
use App\Data\Auth\AuthResultData;
use App\Enums\Auth\AuthDriverEnum;
use App\Models\User;
use App\Services\Auth\PassportTokenService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

readonly class PasswordAuthStrategy implements AuthStrategyInterface
{
    public function __construct(
        private PassportTokenService $passportTokenService,
    ) {}

    /**
     * @throws ConnectionException
     */
    public function authenticate(AuthData $data): AuthResultData
    {
        if (! Auth::guard('web')->validate([
            'email' => $data->email,
            'password' => $data->password,
        ])) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $user = User::query()
            ->where('email', $data->email)
            ->firstOrFail();

        $token = $this->passportTokenService->issuePasswordToken(
            email: $data->email,
            password: $data->password,
        );

        return new AuthResultData(
            user: $user,
            accessToken: $token->accessToken,
            refreshToken: $token->refreshToken,
            tokenType: $token->tokenType,
            expiresIn: $token->expiresIn,
        );
    }

    public function driver(): AuthDriverEnum
    {
        return AuthDriverEnum::Password;
    }
}
