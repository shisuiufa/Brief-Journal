<?php

namespace App\Data\Auth;

use App\Models\User;

final readonly class AuthResultData
{
    public function __construct(
        public User $user,
        public string $accessToken,
        public ?string $refreshToken,
        public string $tokenType,
        public int $expiresIn,
    ) {}
}
