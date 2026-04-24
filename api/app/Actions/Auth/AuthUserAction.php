<?php

namespace App\Actions\Auth;

use App\Contracts\Auth\AuthStrategyResolverInterface;
use App\Contracts\Auth\AuthUserActionInterface;
use App\Data\Auth\AuthData;
use App\Models\User;

readonly class AuthUserAction implements AuthUserActionInterface
{
    public function __construct(
        private AuthStrategyResolverInterface $resolver,
    ) {}

    public function __invoke(AuthData $data): User
    {
        $strategy = $this->resolver->resolve($data->driver);

        return $strategy->authenticate($data);
    }
}
