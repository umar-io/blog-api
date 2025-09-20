<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\BlogPostController;
use Illuminate\Support\Facades\Route;

// Public routes for authentication
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

// Protected routes for authenticated users
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('posts')->controller(BlogPostController::class)->group(function () {
        Route::post('/', 'store');
        Route::put('/{post}', 'update');
        Route::delete('/{post}', 'destroy');
    });
});

// Public routes for posts (no authentication required)
Route::prefix('posts')->controller(BlogPostController::class)->group(function () {
    // Search route must come first to avoid conflicts
    Route::get('/search', 'search');
    // Index route
    Route::get('/', 'index');
    // Show route must come last because it has a wildcard
    Route::get('/{post}', 'show');
});
