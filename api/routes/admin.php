<?php

use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;

Route::middleware('auth:api')->group(function () {
    Route::patch('profile', [ProfileController::class, 'update']);
    Route::patch('profile/password', [ProfileController::class, 'password']);
});

Route::middleware(['auth:api', 'role:admin|super-admin|editor'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
});
