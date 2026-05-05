<?php

namespace App\Data\Auth;

final readonly class AuthTokenData
{
    public function __construct(
        public string $accessToken,
        public string $refreshToken,
        public string $tokenType,
        public ?int $expiresIn = null,
    ) {}
}
