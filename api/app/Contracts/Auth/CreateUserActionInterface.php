<?php

namespace App\Contracts\Auth;

use App\Data\CreateUserData;
use App\Models\User;

interface CreateUserActionInterface
{
    public function execute(CreateUserData $DTO): User;
}
