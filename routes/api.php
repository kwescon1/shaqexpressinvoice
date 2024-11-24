<?php

declare(strict_types=1);

use App\Http\Controllers\Invokable\LoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth routes
    Route::post('/login', LoginController::class)->name('api.auth.login');

    // Protected routes for authenticated users
    Route::middleware('auth:sanctum')->group(function () {
        // Add more routes here that require authentication
    });

    // TODO add invoice stock product routes
});
