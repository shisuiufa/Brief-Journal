<?php

namespace App\Contracts\Admin\User;

use App\Models\User;

interface DeleteUserActionInterface
{
    public function execute(User $user): void;
}
