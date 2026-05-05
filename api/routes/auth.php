<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])
    ->middleware(['guest', 'throttle:login']);

Route::post('/refresh', [AuthController::class, 'refresh'])
    ->middleware('throttle:refresh');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:api');
