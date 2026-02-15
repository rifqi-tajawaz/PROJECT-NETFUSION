<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;

/*
|--------------------------------------------------------------------------
| API Routes - With Rate Limiting
|--------------------------------------------------------------------------
|
| Rate limiting strategy:
| - Login: 5 attempts per minute per IP
| - Password reset request: 3 attempts per 5 minutes per IP
| - Password reset confirm: 3 attempts per minute per IP
| - Registration: 3 attempts per minute per IP
| - Social login: 10 attempts per minute per IP
| - Authenticated routes: 60 requests per minute per user
|
*/

// Login with rate limiting (5 attempts per minute)
Route::post('/login', [ApiAuthController::class, 'login'])
    ->middleware(['throttle:5,1']);

// Registration with rate limiting (3 attempts per minute)
Route::post('/users/register', [ApiAuthController::class, 'register'])
    ->middleware(['throttle:3,1']);

// Password reset request (3 attempts per 5 minutes)
Route::post('/auth/password-reset/request', [ApiAuthController::class, 'requestPasswordReset'])
    ->middleware(['throttle:3,5']);

// Password reset token (3 attempts per 5 minutes)
Route::get('/auth/password-reset/token', [ApiAuthController::class, 'getPasswordResetToken'])
    ->middleware(['throttle:3,5']);

// Password reset confirmation (3 attempts per minute)
Route::post('/auth/password-reset/confirm', [ApiAuthController::class, 'confirmPasswordReset'])
    ->middleware(['throttle:3,1']);

// Social login (10 attempts per minute - higher limit for OAuth retries)
Route::post('/auth/social-login', [ApiAuthController::class, 'socialLogin'])
    ->middleware(['throttle:10,1']);

// Protected routes (require authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    // Logout and authenticated routes with rate limiting
    Route::middleware(['throttle:60,1'])->group(function () {
        Route::post('/logout', [ApiAuthController::class, 'logout']);
        Route::put('/user/password', [ApiAuthController::class, 'changePassword']);
    });
});

// Mikrotik API routes removed (Controllers deleted)

// Payment Webhook (Midtrans)
Route::post('/payment/midtrans/callback', [App\Http\Controllers\PaymentController::class, 'callback']);
