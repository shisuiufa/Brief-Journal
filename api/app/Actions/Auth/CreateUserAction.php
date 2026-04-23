<?php

namespace App\Actions\Auth;

use App\Contracts\Auth\CreateUserActionInterface;
use App\Data\CreateUserData;
use App\Models\User;
use Illuminate\Support\Facades\DB;

readonly class CreateUserAction implements CreateUserActionInterface
{
    public function execute(CreateUserData $DTO): User
    {
        return DB::transaction(function () use ($DTO): User {
            $user = User::create([
                'name' => $DTO->name,
                'email' => $DTO->email,
                'password' => $DTO->password,
            ]);

            $user->assignRole($DTO->role->value);

            return $user;
        });
    }
}
