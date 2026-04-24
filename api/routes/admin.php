<?php

use App\Http\Controllers\Admin\UserController;

Route::middleware(['auth:sanctum', 'role:admin|super-admin|editor'])->group(function () {
    Route::apiResource('users', UserController::class);
});
