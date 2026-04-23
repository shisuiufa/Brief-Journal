<?php

namespace App\Contracts\Auth;

interface LogoutUserActionInterface
{
    public function execute(): void;
}
