<?php

namespace App\Actions\Admin\Profile;

use App\Contracts\Admin\Profile\UpdateProfileActionInterface;
use App\Data\Admin\Profile\ProfileData;
use App\Models\User;

final readonly class UpdateProfileAction implements UpdateProfileActionInterface
{
    public function execute(User $user, ProfileData $data): User
    {
        $user->update([
            'name' => $data->name,
            'email' => $data->email,
        ]);

        return $user->refresh();
    }
}
