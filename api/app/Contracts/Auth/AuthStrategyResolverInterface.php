<?php

namespace App\Contracts\Auth;

use App\Enums\Auth\AuthDriverEnum;

interface AuthStrategyResolverInterface
{
    public function resolve(AuthDriverEnum $driver): AuthStrategyInterface;
}
