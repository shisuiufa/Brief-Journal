<?php

namespace App\Actions;

use App\Data\CreateUserData;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateUserAction
{
    /**
     * @throws Throwable
     */
    public function __invoke(CreateUserData $data): User
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
