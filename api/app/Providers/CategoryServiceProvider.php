<?php

namespace App\Providers;

use App\Actions\Admin\Category\CreateCategoryAction;
use App\Actions\Admin\Category\DestroyCategoryAction;
use App\Actions\Admin\Category\UpdateCategoryAction;
use App\Contracts\Admin\Category\CreateCategoryActionInterface;
use App\Contracts\Admin\Category\DestroyCategoryActionInterface;
use App\Contracts\Admin\Category\UpdateCategoryActionInterface;
use Illuminate\Support\ServiceProvider;

class CategoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            CreateCategoryActionInterface::class,
            CreateCategoryAction::class
        );

        $this->app->bind(
            UpdateCategoryActionInterface::class,
            UpdateCategoryAction::class
        );

        $this->app->bind(
            DestroyCategoryActionInterface::class,
            DestroyCategoryAction::class
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
