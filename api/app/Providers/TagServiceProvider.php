<?php

namespace App\Providers;

use App\Actions\Admin\Tag\CreateTagAction;
use App\Actions\Admin\Tag\DestroyTagAction;
use App\Actions\Admin\Tag\UpdateTagAction;
use App\Contracts\Admin\Tag\CreateTagActionInterface;
use App\Contracts\Admin\Tag\DestroyTagActionInterface;
use App\Contracts\Admin\Tag\UpdateTagActionInterface;
use Illuminate\Support\ServiceProvider;

class TagServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            CreateTagActionInterface::class,
            CreateTagAction::class
        );

        $this->app->bind(
            UpdateTagActionInterface::class,
            UpdateTagAction::class
        );

        $this->app->bind(
            DestroyTagActionInterface::class,
            DestroyTagAction::class
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
