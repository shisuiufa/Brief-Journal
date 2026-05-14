<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });
});

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/populars', [PostController::class, 'populars']);
Route::get('/posts/featured', [PostController::class, 'featured']);
Route::get('/posts/{post:slug}', [PostController::class, 'show']);
Route::get('/tags', [TagController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
