<?php

namespace App\Actions\Admin\User;

use App\Contracts\Admin\User\UpdateUserActionInterface;
use App\Data\Admin\User\UpdateUserData;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class UpdateUserAction implements UpdateUserActionInterface
{
    public function execute(User $user, UpdateUserData $data): User
    {
        return DB::transaction(function () use ($user, $data): User {
            $attributes = [
                'name' => $data->name,
                'email' => $data->email,
            ];

            if (! blank($data->password)) {
                $attributes['password'] = $data->password;
            }

            $user->update($attributes);

            if ($data->role) {
                $user->syncRoles([$data->role->value]);
            }

            return $user->refresh();
        });
    }
}
