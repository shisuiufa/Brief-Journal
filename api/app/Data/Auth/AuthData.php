<?php

namespace App\Data\Auth;

use App\Enums\Auth\AuthDriverEnum;

final readonly class AuthData
{
    public function __construct(
        public AuthDriverEnum $driver,
        public string $email,
        public ?string $password = null,
    ) {}
}
