<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\PostServiceProvider;
use App\Providers\UserServiceProvider;

return [
    UserServiceProvider::class,
    AppServiceProvider::class,
    AuthServiceProvider::class,
    PostServiceProvider::class,
];
