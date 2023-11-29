<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::group(['prefix' => 'users'], function() {
    Route::get('/', [UserController::class, 'getList']);
    Route::post('/', [UserController::class, 'create']);

    Route::middleware('jwt.auth')->group(function() {
        Route::get('/{user}', [UserController::class, 'get']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'delete']);
    });
});

Route::group(['prefix' => 'auth'], function() {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/me', [AuthController::class, 'me']);
});

Route::group(['prefix' => 'products'], function() {
    Route::get('/', [ProductController::class, 'getList']);
    Route::get('/{product:slug}', [ProductController::class, 'get']);

    Route::middleware('jwt.auth')->group(function() {
        Route::post('/', [ProductController::class, 'create']);
        Route::put('/{product:slug}', [ProductController::class, 'update']);
        Route::delete('/{product:slug}', [ProductController::class, 'delete']);
    });
});
