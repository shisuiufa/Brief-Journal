<?php

namespace App\Actions\Auth;

use App\Contracts\Auth\LogoutUserActionInterface;
use Illuminate\Support\Facades\Auth;

readonly class LogoutUserAction implements LogoutUserActionInterface
{
    public function execute(): void
    {
        Auth::guard('web')->logout();

        session()->invalidate();
        session()->regenerateToken();
    }
}
