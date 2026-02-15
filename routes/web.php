<?php

use App\Http\Controllers\Admin\Support\DocumentationController;
use App\Http\Controllers\Admin\Support\FaqController;
use App\Http\Controllers\Admin\Support\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\DeviceVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\DocumentationController as PublicDocumentationController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Support\FaqPublicController;
use App\Http\Controllers\Support\TicketController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Home page
Route::get('/', fn () => Auth::check()
    ? redirect()->route('mikrotik-suite.dashboard')
    : redirect()->route('login')
);

// Authentication Routes
Route::middleware(['throttle:5,1'])->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login')->middleware(['check-login-attempts'])->name('login.post');
    });

    Auth::routes(['verify' => true, 'login' => false]);
});

// Social Auth
Route::controller(SocialAuthController::class)->prefix('auth')->name('social.')->group(function () {
    Route::get('{provider}', 'redirect')->name('redirect');
    Route::get('{provider}/callback', 'callback')->name('callback');
});

// Verification Routes
Route::middleware(['guest'])->prefix('auth')->name('auth.')->group(function () {
    Route::view('verification-required', 'auth.verification-required')->name('verification.required');
    Route::post('verify-device', [DeviceVerificationController::class, 'verify'])->name('verify-device');
});

// Email Verification
Route::controller(VerificationController::class)->group(function () {
    Route::post('/email/resend/guest', 'resendGuest')->name('verification.resend.guest')->middleware(['guest', 'throttle:6,1']);
    Route::post('/email/verify/otp', 'verifyOtp')->name('verification.verify.otp')->middleware(['auth', 'throttle:6,1']);
});

// Public Pages
Route::view('/registration-success', 'auth.registration-success')->name('registration.success')->middleware('guest');
Route::view('/pricing', 'pages.pricing')->name('pricing');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/timeline', 'pages.timeline')->name('timeline');
Route::view('/support', 'pages.support')->name('support');
Route::view('/support/ticket', 'pages.support.ticket')->name('support.ticket');

// Support & Documentation
Route::get('/faq', [FaqPublicController::class, 'index'])->name('faq');
Route::get('/faqs', [FaqPublicController::class, 'index'])->name('faq.index');
Route::controller(PublicDocumentationController::class)->prefix('documentation')->name('documentation')->group(function () {
    Route::get('/{slug?}', 'show')->name('.show');
});

// Language Switch
Route::get('lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // Payment
    Route::controller(PaymentController::class)->prefix('payment')->name('payment.')->group(function () {
        Route::post('/checkout', 'checkout')->name('checkout');
        Route::post('/trial', 'startTrial')->name('trial');
        Route::get('/success', 'success')->name('success');
        Route::get('/failed', 'failed')->name('failed');
    });

    // Support Tickets (User)
    Route::controller(TicketController::class)->prefix('support/ticket')->name('ticket.')->group(function () {
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::post('/{id}/reply', 'reply')->name('reply');
    });
    Route::get('/support/my-tickets', [TicketController::class, 'index'])->name('ticket.index');

    // User Profile
    Route::controller(UserProfileController::class)->prefix('user-profile')->name('user.profile')->group(function () {
        Route::get('/', 'index');
        Route::post('update', 'update')->name('.update');
        Route::post('preferences', 'updatePreferences')->name('.preferences');

        Route::middleware(['password.confirm'])->group(function () {
            Route::post('password', 'updatePassword')->name('.password');
            Route::delete('/', 'destroy')->name('.destroy');
        });
    });

    // Two-Factor Authentication
    Route::view('two-factor-challenge', 'auth.two-factor-challenge')->name('two-factor.challenge');
    Route::post('two-factor-challenge', [TwoFactorController::class, 'verify'])
        ->middleware(['throttle:5,1'])
        ->name('two-factor.verify');

    Route::controller(TwoFactorController::class)->prefix('two-factor')->name('two-factor.')->group(function () {
        Route::post('recovery', 'verifyRecovery')->middleware(['throttle:3,1'])->name('recovery.verify');
        Route::get('recovery', 'showRecoveryForm')->name('recovery');
        Route::post('recovery-codes', 'showRecoveryCodes')->name('recovery-codes');
        Route::post('confirm', 'confirm')->name('confirm');
        Route::post('disable', 'disable')->name('disable');
        Route::get('setup', fn() => redirect()->route('user.profile'))->name('setup');
    });

    // Admin Routes
    Route::post('stop-impersonation', [UserManagementController::class, 'stopImpersonation'])->name('admin.users.stop-impersonation');

    Route::group(['prefix' => 'admin/support', 'as' => 'admin.support.'], function () {
        Route::resource('tickets', AdminTicketController::class);
        Route::post('tickets/{ticket}/reply', [AdminTicketController::class, 'reply'])->name('tickets.reply');
        Route::resource('faqs', FaqController::class);
        Route::post('documentation/upload', [DocumentationController::class, 'uploadImage'])->name('documentation.upload');
        Route::resource('documentation', DocumentationController::class);
    });
});

// Load NetFusion Routes
require __DIR__ . '/netfusion.php';
