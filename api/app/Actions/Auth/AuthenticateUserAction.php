<?php

namespace App\Actions\Auth;

use App\Contracts\Auth\AuthenticateUserActionInterface;
use App\Data\Auth\AuthData;
use App\Factories\Auth\AuthenticationStrategyFactory;
use App\Models\User;

readonly class AuthenticateUserAction implements AuthenticateUserActionInterface
{
    public function __construct(
        private AuthenticationStrategyFactory $factory,
    ) {}

    public function execute(AuthData $data): User
    {
        $strategy = $this->factory->make($data->driver);

        return $strategy->authenticate($data);
    }
}
