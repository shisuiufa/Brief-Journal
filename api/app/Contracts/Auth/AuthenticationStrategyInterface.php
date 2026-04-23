<?php

namespace App\Contracts\Auth;

use App\Data\Auth\AuthData;
use App\Models\User;

interface AuthenticationStrategyInterface
{
    public function authenticate(AuthData $data): User;
}
