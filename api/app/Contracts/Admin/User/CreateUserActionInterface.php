<?php

namespace App\Contracts\Admin\User;

use App\Data\Admin\CreateUserData;
use App\Models\User;

interface CreateUserActionInterface
{
    public function execute(CreateUserData $data): User;
}
