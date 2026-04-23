<?php

namespace App\Factories\Auth;

use App\Contracts\Auth\AuthenticationStrategyInterface;
use App\Enums\Auth\AuthDriverEnum;
use App\Strategies\Auth\PasswordAuthenticationStrategy;

class AuthenticationStrategyFactory
{
    public function make(AuthDriverEnum $driver): AuthenticationStrategyInterface
    {
        return match ($driver) {
            AuthDriverEnum::Password => app(PasswordAuthenticationStrategy::class),
        };
    }
}
