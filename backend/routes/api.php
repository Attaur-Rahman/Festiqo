<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\User\UserController;

// Authentication routes.
Route::prefix('auth')->group(function () {

    // Authenticate user and issue access token.
    Route::post('/login', [AuthController::class, 'login']);

    // Send password reset OTP.
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    // ->middleware('throttle:3,10');

    // Verify password reset OTP.
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    // ->middleware('throttle:10,10');

    // Resend password reset OTP.
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
    // ->middleware('throttle:3,10');

    // Reset password using a verified reset token.
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    // Protected authentication routes.
    Route::middleware('auth:sanctum')->group(function () {

        // Get authenticated user details.
        Route::get('/me', [AuthController::class, 'me']);

        // Revoke the current access token.
        Route::post('/logout', [AuthController::class, 'logout']);

        // Change password for the authenticated user.
        Route::patch('/change-password', [AuthController::class, 'changePassword']);
    });
});

// Protected user management routes.
Route::middleware('auth:sanctum')->prefix('users')->group(function () {

    // List users.
    Route::get('/', [UserController::class, 'index'])
        ->middleware('permission:user.view');

    // Create a new user.
    Route::post('/', [UserController::class, 'store'])
        ->middleware('permission:user.create');

    // Get user details.
    Route::get('/{user}', [UserController::class, 'show'])
        ->middleware('permission:user.view');

    // Update user details.
    Route::put('/{user}', [UserController::class, 'update'])
        ->middleware('permission:user.update');

    // Delete a user.
    Route::delete('/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:user.delete');

    // Update user account status.
    Route::patch('/{user}/status', [UserController::class, 'updateStatus'])
        ->middleware('permission:user.update');

    // Update user role.
    Route::patch('/{user}/role', [UserController::class, 'updateRole'])
        ->middleware('permission:user.update');
});

// Protected category management routes.
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/categories', [CategoryController::class, 'index'])
        ->middleware('permission:category.view');

    Route::post('/categories', [CategoryController::class, 'store'])
        ->middleware('permission:category.create');

    Route::get('/categories/{category}', [CategoryController::class, 'show'])
        ->middleware('permission:category.view');

    Route::put('/categories/{category}', [CategoryController::class, 'update'])
        ->middleware('permission:category.update');

    Route::patch('/categories/{category}', [CategoryController::class, 'update'])
        ->middleware('permission:category.update');

    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
        ->middleware('permission:category.delete');
});
