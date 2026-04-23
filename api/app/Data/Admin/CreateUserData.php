<?php

namespace App\Data\Admin;

use App\Enums\Access\RoleEnum;

final readonly class CreateUserData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public RoleEnum $role,
    ) {}
}
