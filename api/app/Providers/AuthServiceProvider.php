<?php

namespace App\Providers;

use App\Actions\Auth\AuthUserAction;
use App\Actions\Auth\LogoutUserAction;
use App\Contracts\Auth\AuthStrategyResolverInterface;
use App\Contracts\Auth\AuthUserActionInterface;
use App\Contracts\Auth\LogoutUserActionInterface;
use App\Resolvers\Auth\AuthStrategyResolver;
use App\Strategies\Auth\PasswordAuthStrategy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            AuthUserActionInterface::class,
            AuthUserAction::class
        );

        $this->app->bind(
            LogoutUserActionInterface::class,
            LogoutUserAction::class
        );

        $this->app->bind(
            AuthStrategyResolverInterface::class,
            AuthStrategyResolver::class
        );

        $this->app->tag([
            PasswordAuthStrategy::class,
        ], 'auth.strategies');

        $this->app->when(AuthStrategyResolver::class)
            ->needs('$strategies')
            ->giveTagged('auth.strategies');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}
