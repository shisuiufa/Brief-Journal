<?php

namespace App\Contracts\Auth;

use App\Data\Auth\AuthData;
use App\Models\User;

interface AuthenticateUserActionInterface
{
    public function execute(AuthData $data): User;
}
