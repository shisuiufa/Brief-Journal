<?php

namespace App\Actions\Admin\User;

use App\Contracts\Admin\User\UpdateUserActionInterface;
use App\Data\Admin\UpdateUserData;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class UpdateUserAction implements UpdateUserActionInterface
{
    public function execute(User $user, UpdateUserData $data): User
    {
        return DB::transaction(function () use ($user, $data): User {
            $user->update([
                'name' => $data->name,
                'email' => $data->email,
            ]);

            if ($data->role) {
                $user->syncRoles([$data->role->value]);
            }

            return $user->refresh();
        });
    }
}
