<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now cleaner than ever!
|
*/

// Home page route - redirect appropriately
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('mikrotik-suite.dashboard')
        : redirect()->route('login');
});

// Rate Limited Auth Routes with Login Attempt Checking
Route::middleware(['throttle:5,1'])->group(function () {
    // Show login form (GET)
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])
        ->name('login');

    // Process login (POST) with attempt checking
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])
        ->middleware(['check-login-attempts'])
        ->name('login.post');

    // Other auth routes
    Auth::routes(['verify' => true, 'login' => false]);
});

// Registration success page
Route::get('/registration-success', function () {
    return view('auth.registration-success');
})->name('registration.success')->middleware('guest');

// Pricing Page
Route::get('/pricing', function () {
    return view('pages.pricing');
})->name('pricing');

// Payment Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/payment/checkout', [App\Http\Controllers\PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/trial', [App\Http\Controllers\PaymentController::class, 'startTrial'])->name('payment.trial');
    Route::get('/payment/success', [App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed', [App\Http\Controllers\PaymentController::class, 'failed'])->name('payment.failed');
});

// Terms & Conditions
Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

// Timeline
Route::get('/timeline', function () {
    return view('pages.timeline');
})->name('timeline');

// FAQ
Route::get('/faq', [App\Http\Controllers\Support\FaqPublicController::class, 'index'])->name('faq');
// FAQ alias for consistency
Route::get('/faqs', [App\Http\Controllers\Support\FaqPublicController::class, 'index'])->name('faq.index');

// Support
Route::get('/support', function () {
    return view('pages.support');
})->name('support');

Route::get('/support/ticket', function () {
    return view('pages.support.ticket');
})->name('support.ticket');

Route::post('/support/ticket', [App\Http\Controllers\Support\TicketController::class, 'store'])->name('ticket.store');
Route::get('/support/my-tickets', [App\Http\Controllers\Support\TicketController::class, 'index'])->name('ticket.index')->middleware('auth');
Route::get('/support/ticket/{id}', [App\Http\Controllers\Support\TicketController::class, 'show'])->name('ticket.show')->middleware('auth');
Route::post('/support/ticket/{id}/reply', [App\Http\Controllers\Support\TicketController::class, 'reply'])->name('ticket.reply')->middleware('auth');

// Documentation
Route::get('/documentation/{slug?}', [App\Http\Controllers\DocumentationController::class, 'show'])->name('documentation.show');
Route::get('/documentation', [App\Http\Controllers\DocumentationController::class, 'show'])->name('documentation'); // Alias for index

Route::get('/documentation', [App\Http\Controllers\DocumentationController::class, 'show'])->name('documentation'); // Alias for index

// Guest Resend Verification
Route::post('/email/resend/guest', [App\Http\Controllers\Auth\VerificationController::class, 'resendGuest'])
    ->name('verification.resend.guest')
    ->middleware(['guest', 'throttle:6,1']);

// OTP Verification Route
Route::post('/email/verify/otp', [App\Http\Controllers\Auth\VerificationController::class, 'verifyOtp'])
    ->name('verification.verify.otp')
    ->middleware(['auth', 'throttle:6,1']);

Route::get('lang/{locale}', [App\Http\Controllers\LanguageController::class, 'switch'])->name('lang.switch');


// 2FA Routes & User Profile (Common)
Route::middleware(['auth'])->group(function () {
    // User Profile
    Route::prefix('user-profile')->group(function () {
        Route::get('/', [UserProfileController::class, 'index'])->name('user.profile');
        Route::post('update', [UserProfileController::class, 'update'])->name('user.profile.update');
        Route::post('preferences', [UserProfileController::class, 'updatePreferences'])->name('user.profile.preferences');

        // Sensitive actions require password confirmation
        Route::middleware(['password.confirm'])->group(function () {
            Route::post('password', [UserProfileController::class, 'updatePassword'])->name('user.profile.password');
            Route::delete('/', [UserProfileController::class, 'destroy'])->name('user.profile.destroy');
        });
    });

    // Two-Factor Authentication Challenge
    Route::get('two-factor-challenge', function () {
        return view('auth.two-factor-challenge');
    })->name('two-factor.challenge');

    // 2FA verification with rate limiting (5 attempts per minute)
    Route::post('two-factor-challenge', [App\Http\Controllers\TwoFactorController::class, 'verify'])
        ->middleware(['throttle:5,1'])
        ->name('two-factor.verify');

    // Two-Factor Authentication Management
    Route::prefix('two-factor')->name('two-factor.')->group(function () {
        // Recovery with rate limiting (3 attempts per minute)
        Route::post('recovery', [App\Http\Controllers\TwoFactorController::class, 'verifyRecovery'])
            ->middleware(['throttle:3,1'])
            ->name('recovery.verify');

        // Other 2FA management routes
        Route::get('recovery', [App\Http\Controllers\TwoFactorController::class, 'showRecoveryForm'])->name('recovery');
        Route::post('recovery-codes', [App\Http\Controllers\TwoFactorController::class, 'showRecoveryCodes'])->name('recovery-codes');
        Route::post('confirm', [App\Http\Controllers\TwoFactorController::class, 'confirm'])->name('confirm');
        Route::post('disable', [App\Http\Controllers\TwoFactorController::class, 'disable'])->name('disable');
        Route::get('setup', function () {
            return redirect()->route('user.profile');
        })->name('setup');
    });

    // Stop Impersonation
    Route::post('stop-impersonation', [App\Http\Controllers\Admin\UserManagementController::class, 'stopImpersonation'])->name('admin.users.stop-impersonation');

    // Admin Ticket Management
    Route::group(['prefix' => 'admin/support', 'as' => 'admin.support.'], function () {
        Route::resource('tickets', App\Http\Controllers\Admin\Support\TicketController::class);
        Route::post('tickets/{ticket}/reply', [App\Http\Controllers\Admin\Support\TicketController::class, 'reply'])->name('tickets.reply');
        Route::resource('faqs', App\Http\Controllers\Admin\Support\FaqController::class);
        Route::post('documentation/upload', [App\Http\Controllers\Admin\Support\DocumentationController::class, 'uploadImage'])->name('documentation.upload');
        Route::resource('documentation', App\Http\Controllers\Admin\Support\DocumentationController::class);
    });
});

// Additional verification routes
Route::middleware(['guest'])->prefix('auth')->name('auth.')->group(function () {
    Route::get('verification-required', function () {
        return view('auth.verification-required');
    })->name('verification.required');

    Route::post('verify-device', [App\Http\Controllers\Auth\DeviceVerificationController::class, 'verify'])->name('verify-device');
});

// Load NetFusion Replica Routes
require __DIR__ . '/netfusion.php';

// Social Authentication Routes (Wildcard must be last)
Route::get('auth/{provider}', [App\Http\Controllers\Auth\SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('auth/{provider}/callback', [App\Http\Controllers\Auth\SocialAuthController::class, 'callback'])->name('social.callback');
