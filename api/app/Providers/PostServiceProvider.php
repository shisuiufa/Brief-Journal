<?php

namespace App\Providers;

use App\Actions\Admin\Post\CreatePostAction;
use App\Actions\Admin\Post\UpdatePostAction;
use App\Actions\Post\IncrementPostViewsAction;
use App\Contracts\Admin\Post\CreatePostActionInterface;
use App\Contracts\Admin\Post\UpdatePostActionInterface;
use App\Contracts\Post\IncrementPostViewsActionInterface;
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

        $this->app->bind(
            IncrementPostViewsActionInterface::class,
            IncrementPostViewsAction::class
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
