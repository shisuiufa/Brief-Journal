<?php

namespace App\Strategies\Auth;

use App\Contracts\Auth\AuthStrategyInterface;
use App\Data\Auth\AuthData;
use App\Enums\Auth\AuthDriverEnum;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PasswordAuthStrategy implements AuthStrategyInterface
{
    public function authenticate(AuthData $data): User
    {
        if (! Auth::attempt([
            'email' => $data->email,
            'password' => $data->password,
        ])) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        session()->regenerate();

        return Auth::user();
    }

    public function driver(): AuthDriverEnum
    {
        return AuthDriverEnum::Password;
    }
}
