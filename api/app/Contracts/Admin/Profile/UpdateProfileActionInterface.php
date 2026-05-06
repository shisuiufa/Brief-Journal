<?php

namespace App\Contracts\Admin\Profile;

use App\Data\Admin\Profile\ProfileData;
use App\Models\User;

interface UpdateProfileActionInterface
{
    public function execute(User $user, ProfileData $data): User;
}
