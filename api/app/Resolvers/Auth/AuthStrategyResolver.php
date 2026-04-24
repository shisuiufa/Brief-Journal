<?php

namespace App\Resolvers\Auth;

use App\Contracts\Auth\AuthStrategyInterface;
use App\Contracts\Auth\AuthStrategyResolverInterface;
use App\Enums\Auth\AuthDriverEnum;
use InvalidArgumentException;

readonly class AuthStrategyResolver implements AuthStrategyResolverInterface
{
    /**
     * @param  iterable<AuthStrategyInterface>  $strategies
     */
    public function __construct(
        private iterable $strategies,
    ) {}

    public function resolve(AuthDriverEnum $driver): AuthStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->driver() === $driver) {
                return $strategy;
            }
        }

        throw new InvalidArgumentException(
            "Authentication strategy for driver [{$driver->value}] not found."
        );
    }
}
