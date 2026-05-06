<?php

namespace App\Data\Admin\Profile;

final readonly class ProfileData
{
    public function __construct(
        public string $name,
        public string $email,
    ) {}
}
