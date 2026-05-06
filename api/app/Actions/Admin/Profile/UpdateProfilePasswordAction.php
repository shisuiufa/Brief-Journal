<?php

namespace App\Actions\Admin\Profile;

use App\Contracts\Admin\Profile\UpdateProfilePasswordActionInterface;
use App\Data\Admin\Profile\ProfilePasswordData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final readonly class UpdateProfilePasswordAction implements UpdateProfilePasswordActionInterface
{
    public function execute(User $user, ProfilePasswordData $data): void
    {
        if (! Hash::check($data->currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password is incorrect.'],
            ]);
        }

        $user->update([
            'password' => $data->password,
        ]);
    }
}
