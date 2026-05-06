<?php

namespace App\Data\Admin\Profile;

final readonly class ProfilePasswordData
{
    public function __construct(
        public string $currentPassword,
        public string $password,
    ) {}
}
