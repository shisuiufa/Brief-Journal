<?php

namespace App\Data\Admin\User;

use App\Enums\Access\RoleEnum;

final readonly class UpdateUserData
{
    public function __construct(
        public string $name,
        public string $email,
        public ?RoleEnum $role = null,
    ) {}
}
