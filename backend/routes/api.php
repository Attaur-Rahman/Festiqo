<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\Auth\AuthController;

// Health check endpoint.
Route::get('/health', HealthController::class);

Route::prefix('auth')->group(function () {

    // Authenticate user and issue access token.
    Route::post('/login', [AuthController::class, 'login']);

    // Send password reset OTP.
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    // ->middleware('throttle:3,10');

    // Verify password reset OTP.
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])
        ->middleware('throttle:10,10');

    // Resend password reset OTP.
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])
        ->middleware('throttle:3,10');

    // Reset password using a verified reset token.
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {

        // Get authenticated user details.
        Route::get('/me', [AuthController::class, 'me']);

        // Revoke the current access token.
        Route::post('/logout', [AuthController::class, 'logout']);

        // Change password for the authenticated user.
        Route::patch('/change-password', [AuthController::class, 'changePassword']);
    });
});
