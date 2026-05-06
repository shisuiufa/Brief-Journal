<?php

namespace App\Providers;

use App\Actions\Admin\Profile\UpdateProfileAction;
use App\Actions\Admin\Profile\UpdateProfilePasswordAction;
use App\Actions\Admin\User\CreateUserAction;
use App\Actions\Admin\User\DeleteUserAction;
use App\Actions\Admin\User\UpdateUserAction;
use App\Contracts\Admin\Profile\UpdateProfileActionInterface;
use App\Contracts\Admin\Profile\UpdateProfilePasswordActionInterface;
use App\Contracts\Admin\User\CreateUserActionInterface;
use App\Contracts\Admin\User\DeleteUserActionInterface;
use App\Contracts\Admin\User\UpdateUserActionInterface;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            CreateUserActionInterface::class,
            CreateUserAction::class
        );

        $this->app->bind(
            UpdateUserActionInterface::class,
            UpdateUserAction::class
        );

        $this->app->bind(
            DeleteUserActionInterface::class,
            DeleteUserAction::class
        );

        $this->app->bind(
            UpdateProfileActionInterface::class,
            UpdateProfileAction::class
        );

        $this->app->bind(
            UpdateProfilePasswordActionInterface::class,
            UpdateProfilePasswordAction::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
