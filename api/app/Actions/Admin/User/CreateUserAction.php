<?php

namespace App\Actions\Admin\User;

use App\Contracts\Admin\User\CreateUserActionInterface;
use App\Data\Admin\CreateUserData;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class CreateUserAction implements CreateUserActionInterface
{
    public function execute(CreateUserData $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => $data->password,
            ]);

            $user->assignRole($data->role->value);

            return $user;
        });
    }
}
