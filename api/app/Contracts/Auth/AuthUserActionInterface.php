<?php

namespace App\Contracts\Auth;

use App\Data\Auth\AuthData;
use App\Models\User;

interface AuthUserActionInterface
{
    public function __invoke(AuthData $data): User;
}
