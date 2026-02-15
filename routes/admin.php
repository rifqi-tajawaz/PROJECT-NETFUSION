<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\SystemSettingsController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Registered via bootstrap/app.php
| Prefix: /admin
| Name: admin.
| Middleware: auth, admin
|
*/

// Activity Logs
Route::get('activity-logs/export', [ActivityLogController::class, 'export'])->name('activity-logs.export');
Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

// User Management
Route::post('users/{user}/impersonate', [UserManagementController::class, 'impersonate'])->name('users.impersonate');
Route::resource('users', UserManagementController::class)->except(['create', 'show']);

// System Settings
Route::post('settings/update', [SystemSettingsController::class, 'update'])->name('settings.update');
Route::post('settings/toggle-registration', [SystemSettingsController::class, 'toggleRegistration'])->name('settings.toggle-registration');

// Stop Impersonation (Accessible by auth users - but grouped here for logic, though originally has no 'admin' prefix but checks middleware)
// Wait, 'stop-impersonation' in original web.php is OUTSIDE the admin prefix group but has 'admin.users.' name.
// Let's check web.php again. It was:
// Route::post('stop-impersonation', ...)->name('admin.users.stop-impersonation');
// It was OUTSIDE the admin middleware group in lines 112-113.
// So we should NOT put it here if this file wraps with 'admin' middleware.
// OR we put it here but exclude it from the group in bootstrap.
// Better: Leave 'stop-impersonation' in web.php or a general 'auth.php' since any user can stop impersonation (if they are impersonating).
// Actually, only admins impersonate. But when they are impersonating, they look like a user. 
// So the 'stop' route must be accessible by the 'impersonated' user (who has the permissions of the target).
// Thus it cannot be behind 'admin' middleware if the target user is not an admin.
// I will leave 'stop-impersonation' in web.php for now or auth.php.
