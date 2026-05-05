<?php

namespace App\Contracts\Auth;

use App\Data\Auth\AuthData;
use App\Data\Auth\AuthResultData;

interface AuthUserActionInterface
{
    public function __invoke(AuthData $data): AuthResultData;
}
