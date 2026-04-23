<?php

use App\Http\Controllers\Admin\UserController;

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('users', UserController::class);
});
