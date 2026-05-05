<?php

namespace App\Contracts\Auth;

use App\Data\Auth\AuthData;
use App\Data\Auth\AuthResultData;
use App\Enums\Auth\AuthDriverEnum;

interface AuthStrategyInterface
{
    public function authenticate(AuthData $data): AuthResultData;

    public function driver(): AuthDriverEnum;
}
