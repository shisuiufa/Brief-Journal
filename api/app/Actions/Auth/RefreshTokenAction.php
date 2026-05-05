<?php

namespace App\Actions\Auth;

use App\Contracts\Auth\RefreshTokenActionInterface;
use App\Data\Auth\AuthTokenData;
use App\Services\Auth\PassportTokenService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Validation\ValidationException;

readonly class RefreshTokenAction implements RefreshTokenActionInterface
{
    public function __construct(
        private PassportTokenService $passportTokenService,
    ) {}

    /**
     * @throws ConnectionException
     */
    public function execute(?string $refreshToken): AuthTokenData
    {
        if (! $refreshToken) {
            throw ValidationException::withMessages([
                'refresh_token' => ['Refresh token is missing.'],
            ]);
        }

        return $this->passportTokenService->refresh($refreshToken);
    }
}
