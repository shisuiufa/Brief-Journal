<?php

namespace App\Contracts\Auth;

use App\Data\Auth\AuthData;
use App\Enums\Auth\AuthDriverEnum;
use App\Models\User;

interface AuthStrategyInterface
{
    public function authenticate(AuthData $data): User;

    public function driver(): AuthDriverEnum;
}
