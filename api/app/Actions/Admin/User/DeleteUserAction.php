<?php

namespace App\Actions\Admin\User;

use App\Contracts\Admin\User\DeleteUserActionInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final readonly class DeleteUserAction implements DeleteUserActionInterface
{
    public function execute(User $user): void
    {
        if (Auth::id() === $user->id) {
            throw ValidationException::withMessages([
                'user' => ['You cannot delete yourself.'],
            ]);
        }

        $user->delete();
    }
}
