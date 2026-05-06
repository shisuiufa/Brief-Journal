<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\CategoryServiceProvider;
use App\Providers\PostServiceProvider;
use App\Providers\TagServiceProvider;
use App\Providers\UserServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    CategoryServiceProvider::class,
    PostServiceProvider::class,
    TagServiceProvider::class,
    UserServiceProvider::class,
];
