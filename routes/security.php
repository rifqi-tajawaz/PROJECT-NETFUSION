<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Security\SecurityDashboardController;
use App\Http\Controllers\Security\DeviceManagementController;
use App\Http\Controllers\Security\SessionManagementController;
use App\Http\Controllers\Security\SecurityLogController;
use App\Http\Controllers\Security\PasswordManagementController;

/*
|--------------------------------------------------------------------------
| Security Dashboard Routes
|--------------------------------------------------------------------------
|
| Registered via bootstrap/app.php
| Prefix: /account
| Name: account.
| Middleware: auth
|
*/

Route::get('security', [SecurityDashboardController::class, 'index'])->name('security');
Route::post('security/device/{id}/logout', [SecurityDashboardController::class, 'logoutDevice'])->name('security.device.logout');
Route::post('security/avatar', [SecurityDashboardController::class, 'updateAvatar'])->name('security.avatar.update');

// Enhanced Security Features
Route::prefix('security')->name('security.')->group(function () {
    // Device Management
    Route::get('devices', [DeviceManagementController::class, 'index'])->name('devices.index');
    Route::post('devices/{id}/trust', [DeviceManagementController::class, 'trust'])->name('devices.trust');
    Route::post('devices/{id}/revoke', [DeviceManagementController::class, 'revoke'])->name('devices.revoke');
    Route::post('devices/revoke-all', [DeviceManagementController::class, 'revokeAll'])->name('devices.revoke-all');

    // Session Management
    Route::get('sessions', [SessionManagementController::class, 'index'])->name('sessions.index');
    Route::post('sessions/{id}/terminate', [SessionManagementController::class, 'terminate'])->name('sessions.terminate');
    Route::post('sessions/terminate-others', [SessionManagementController::class, 'terminateOthers'])->name('sessions.terminate-others');
    Route::post('sessions/terminate-all', [SessionManagementController::class, 'terminateAll'])->name('sessions.terminate-all');

    // Security Logs
    Route::get('logs', [SecurityLogController::class, 'index'])->name('logs.index');
    Route::get('logs/export', [SecurityLogController::class, 'export'])->name('logs.export');

    // Password Management
    Route::get('password', [PasswordManagementController::class, 'index'])->name('password.index');
    Route::post('password/change', [PasswordManagementController::class, 'change'])->name('password.change');
    Route::post('password/check-strength', [PasswordManagementController::class, 'checkStrength'])->name('password.check-strength');
});
