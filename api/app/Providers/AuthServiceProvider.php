<?php

namespace App\Providers;

use App\Actions\Auth\AuthenticateUserAction;
use App\Actions\Auth\CreateUserAction;
use App\Actions\Auth\LogoutUserAction;
use App\Contracts\Auth\AuthenticateUserActionInterface;
use App\Contracts\Auth\CreateUserActionInterface;
use App\Contracts\Auth\LogoutUserActionInterface;
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
            AuthenticateUserActionInterface::class,
            AuthenticateUserAction::class
        );

        $this->app->bind(
            LogoutUserActionInterface::class,
            LogoutUserAction::class
        );

        $this->app->bind(
            CreateUserActionInterface::class,
            CreateUserAction::class
        );
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
