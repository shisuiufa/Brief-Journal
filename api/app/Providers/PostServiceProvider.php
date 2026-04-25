<?php

namespace App\Providers;

use App\Actions\Admin\Post\CreatePostAction;
use App\Actions\Admin\Post\UpdatePostAction;
use App\Contracts\Admin\Post\CreatePostActionInterface;
use App\Contracts\Admin\Post\UpdatePostActionInterface;
use Illuminate\Support\ServiceProvider;

class PostServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            CreatePostActionInterface::class,
            CreatePostAction::class
        );

        $this->app->bind(
            UpdatePostActionInterface::class,
            UpdatePostAction::class
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
