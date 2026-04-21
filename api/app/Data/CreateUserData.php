<?php

namespace App\Data;

use App\Enums\RoleEnum;

final readonly class CreateUserData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public RoleEnum $role,
    ) {}
}
