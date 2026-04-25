<?php

namespace App\Providers;

use App\Contracts\Media\ImageStorageInterface;
use App\Services\Media\ImageStorageService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ImageStorageInterface::class, ImageStorageService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
