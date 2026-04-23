<?php

namespace App\Contracts\Admin\User;

use App\Data\Admin\UpdateUserData;
use App\Models\User;

interface UpdateUserActionInterface
{
    public function execute(User $user, UpdateUserData $data): User;
}
