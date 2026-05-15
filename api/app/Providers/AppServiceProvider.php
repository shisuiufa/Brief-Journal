<?php

namespace App\Providers;

use App\Contracts\Media\ImageStorageInterface;
use App\Contracts\Realtime\RealtimePublisherInterface;
use App\Services\Image\ImageStorageService;
use App\Services\Realtime\RealtimePublisher;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ImageStorageInterface::class, ImageStorageService::class);
        $this->app->bind(RealtimePublisherInterface::class, RealtimePublisher::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::enablePasswordGrant();

        $passportKeyPath = config('passport.key_path');

        if (is_string($passportKeyPath) && $passportKeyPath !== '' && is_dir($passportKeyPath)) {
            Passport::loadKeysFrom($passportKeyPath);
        }
    }
}
