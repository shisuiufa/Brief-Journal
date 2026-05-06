<?php

namespace App\Contracts\Admin\Profile;

use App\Data\Admin\Profile\ProfilePasswordData;
use App\Models\User;

interface UpdateProfilePasswordActionInterface
{
    public function execute(User $user, ProfilePasswordData $data): void;
}
