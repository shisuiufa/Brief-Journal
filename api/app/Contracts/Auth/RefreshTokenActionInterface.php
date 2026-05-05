<?php

namespace App\Contracts\Auth;

use App\Data\Auth\AuthTokenData;

interface RefreshTokenActionInterface
{
    public function execute(?string $refreshToken): AuthTokenData;
}
