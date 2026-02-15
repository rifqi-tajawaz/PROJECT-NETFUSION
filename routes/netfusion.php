<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NetFusion\DashboardController;
use App\Http\Controllers\NetFusion\HotspotUserController;
use App\Http\Controllers\NetFusion\ActiveUserController;
use App\Http\Controllers\NetFusion\PppController;
use App\Http\Controllers\NetFusion\ReportController;
use App\Http\Controllers\NetFusion\SettingsController;
use App\Http\Controllers\NetFusion\ToolsController;
use App\Http\Controllers\NetFusion\SystemController;

Route::middleware(['auth', 'two-factor', 'router.connected'])->prefix('mikrotik-suite/netfusion')->name('mikrotik-suite.netfusion.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/live', [DashboardController::class, 'liveData'])->name('dashboard.live');

    // Hotspot Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [HotspotUserController::class, 'index'])->name('index');
        Route::get('/add', [HotspotUserController::class, 'create'])->name('create');
        Route::post('/', [HotspotUserController::class, 'store'])->name('store');
        Route::get('/generate', [HotspotUserController::class, 'generate'])->name('generate');
        Route::post('/batch', [HotspotUserController::class, 'storeBatch'])->name('store-batch');
        Route::get('/batches', [HotspotUserController::class, 'batches'])->name('batches');
        Route::delete('/batches', [HotspotUserController::class, 'destroyBatch'])->name('destroy-batch');

        // Actions
        Route::get('/{id}/edit', [HotspotUserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [HotspotUserController::class, 'update'])->name('update');
        Route::delete('/{id}', [HotspotUserController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/enable', [HotspotUserController::class, 'enable'])->name('enable');
        Route::post('/{id}/disable', [HotspotUserController::class, 'disable'])->name('disable');
        Route::post('/{id}/reset', [HotspotUserController::class, 'reset'])->name('reset');

        // Safe Actions (via POST/Request Body for modal/safe operations)
        Route::post('/details-action', [HotspotUserController::class, 'detailsAction'])->name('details-action');
        Route::post('/edit-action', [HotspotUserController::class, 'editAction'])->name('edit-action');
        Route::post('/update-action', [HotspotUserController::class, 'updateAction'])->name('update-action');
        Route::post('/reset-action', [HotspotUserController::class, 'resetAction'])->name('reset-action');
        Route::post('/destroy-action', [HotspotUserController::class, 'destroyAction'])->name('destroy-action');
        Route::post('/enable-action', [HotspotUserController::class, 'enableAction'])->name('enable-action');
        Route::post('/disable-action', [HotspotUserController::class, 'disableAction'])->name('disable-action');

        // Batch/Bulk Actions
        Route::post('/destroy-by-comment', [HotspotUserController::class, 'destroyByComment'])->name('destroy-by-comment');
        Route::post('/destroy-expired', [HotspotUserController::class, 'destroyExpired'])->name('destroy-expired');
        Route::post('/bulk-action', [HotspotUserController::class, 'bulkAction'])->name('bulk-action');

        // Export
        Route::get('/export/csv', [HotspotUserController::class, 'exportCsv'])->name('export-csv');
        Route::get('/export/script', [HotspotUserController::class, 'exportScript'])->name('export-script');
    });

    // Active Users
    Route::prefix('active')->name('active.')->group(function () {
        Route::get('/', [ActiveUserController::class, 'index'])->name('index');
        Route::get('/live', [ActiveUserController::class, 'liveData'])->name('live');
        Route::post('/{id}/disconnect', [ActiveUserController::class, 'disconnect'])->name('disconnect');
        Route::post('/disconnect-multiple', [ActiveUserController::class, 'disconnectMultiple'])->name('disconnect-multiple');
    });

    // Hosts
    Route::prefix('hosts')->name('hosts.')->group(function () {
        Route::get('/', [App\Http\Controllers\NetFusion\HotspotHostController::class, 'index'])->name('index');
        Route::delete('/{id}', [App\Http\Controllers\NetFusion\HotspotHostController::class, 'destroy'])->name('destroy');
        // Route::post('/{id}/binding', [HotspotHostController::class, 'makeBinding'])->name('make-binding'); // Future feature?
    });

    // IP Bindings
    Route::prefix('ip-binding')->name('ip-binding.')->group(function () {
        Route::get('/', [App\Http\Controllers\NetFusion\IpBindingController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\NetFusion\IpBindingController::class, 'store'])->name('store');
        Route::put('/{id}', [App\Http\Controllers\NetFusion\IpBindingController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\NetFusion\IpBindingController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/enable', [App\Http\Controllers\NetFusion\IpBindingController::class, 'enable'])->name('enable');
        Route::post('/{id}/disable', [App\Http\Controllers\NetFusion\IpBindingController::class, 'disable'])->name('disable');
    });

    // Cookies
    Route::prefix('cookies')->name('cookies.')->group(function () {
        Route::get('/', [App\Http\Controllers\NetFusion\CookieController::class, 'index'])->name('index');
        Route::delete('/{id}', [App\Http\Controllers\NetFusion\CookieController::class, 'destroy'])->name('destroy');
    });

    // Profiles
    Route::resource('profiles', App\Http\Controllers\NetFusion\UserProfileController::class);

    // Printing (Quick Print)
    Route::get('/printing', [App\Http\Controllers\NetFusion\PrintingController::class, 'index'])->name('printing.index');

    // PPP (Secrets & Profiles)
    Route::prefix('ppp')->name('ppp.')->group(function () {
        // Secrets
        Route::get('secrets', [App\Http\Controllers\NetFusion\PppController::class, 'secrets'])->name('secrets.index');
        Route::post('secrets', [App\Http\Controllers\NetFusion\PppController::class, 'storeSecret'])->name('secrets.store');
        Route::delete('secrets/{id}', [App\Http\Controllers\NetFusion\PppController::class, 'destroySecret'])->name('secrets.destroy');

        // Profiles
        Route::get('profiles', [App\Http\Controllers\NetFusion\PppController::class, 'profiles'])->name('profiles.index');
        Route::post('profiles', [App\Http\Controllers\NetFusion\PppController::class, 'storeProfile'])->name('profiles.store');
        Route::delete('profiles/{id}', [App\Http\Controllers\NetFusion\PppController::class, 'destroyProfile'])->name('profiles.destroy');

        // Active
        Route::get('active', [App\Http\Controllers\NetFusion\PppController::class, 'active'])->name('active.index');
        Route::post('/active/{id}/disconnect', [PppController::class, 'disconnect'])->name('active.disconnect');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index'); // Selling Report
        Route::post('/', [ReportController::class, 'storeSelling'])->name('store');
        Route::delete('/{id}', [ReportController::class, 'destroySelling'])->name('destroy');
        Route::get('/export/csv', [ReportController::class, 'exportSellingCsv'])->name('export-csv');
        Route::get('/print', [ReportController::class, 'printSelling'])->name('print-view');

        Route::get('/logs', [ReportController::class, 'logs'])->name('logs');
    });



    // DHCP Module
    Route::prefix('dhcp')->name('dhcp.')->group(function () {
        Route::get('leases', [App\Http\Controllers\NetFusion\DhcpController::class, 'index'])->name('leases.index');
        Route::delete('leases/{id}', [App\Http\Controllers\NetFusion\DhcpController::class, 'destroy'])->name('leases.destroy');
        Route::post('leases/{id}/static', [App\Http\Controllers\NetFusion\DhcpController::class, 'makeStatic'])->name('leases.static');
    });



    // System Tools
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('/', [SystemController::class, 'index'])->name('index');
        Route::post('reboot', [SystemController::class, 'reboot'])->name('reboot');
        Route::post('shutdown', [SystemController::class, 'shutdown'])->name('shutdown');

        // Scheduler
        Route::get('scheduler', [SystemController::class, 'scheduler'])->name('scheduler');
        Route::post('scheduler', [SystemController::class, 'storeScheduler'])->name('scheduler.store');
        Route::delete('scheduler/{id}', [SystemController::class, 'destroyScheduler'])->name('scheduler.destroy');
        Route::post('scheduler/{id}/toggle', [SystemController::class, 'toggleScheduler'])->name('scheduler.toggle');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/save', [SettingsController::class, 'saveSession'])->name('save');
        Route::delete('/{id}', [SettingsController::class, 'deleteSession'])->name('destroy');
        Route::post('/{id}/connect', [SettingsController::class, 'connect'])->name('connect');
        Route::post('/disconnect', [SettingsController::class, 'disconnect'])->name('disconnect');
        Route::post('/upload-logo', [SettingsController::class, 'uploadLogo'])->name('upload-logo');
        Route::post('/{id}/ping', [SettingsController::class, 'pingSession'])->name('ping');

        Route::get('/export', [SettingsController::class, 'exportSessions'])->name('export');
        Route::post('/import', [SettingsController::class, 'importSessions'])->name('import');


    });

    // Tools (Templates, etc) - MOVED OUTSIDE to avoid connection hang
    // Route::prefix('tools')->name('tools.')->group(function () {
    //     Route::get('/upload-logo', [ToolsController::class, 'uploadLogo'])->name('upload-logo');
    //     Route::get('/template-editor', [ToolsController::class, 'templateEditor'])->name('template-editor');
    // });

});

// Independent Tools (Offline Safe)
Route::middleware(['auth', 'two-factor'])->prefix('mikrotik-suite/netfusion/tools')->name('mikrotik-suite.netfusion.tools.')->group(function () {
    Route::get('/upload-logo', [ToolsController::class, 'uploadLogo'])->name('upload-logo');
    Route::get('/template-editor', [ToolsController::class, 'templateEditor'])->name('template-editor');
    Route::post('/template-editor/save', [ToolsController::class, 'saveTemplate'])->name('template-editor.save');
    Route::post('/template-editor/reset', [ToolsController::class, 'resetTemplate'])->name('template-editor.reset');
    Route::post('/template-editor/preview', [ToolsController::class, 'previewTemplate'])->name('template-editor.preview');

    // Also allow uploading without connection
    Route::post('/upload-logo-process', [SettingsController::class, 'uploadLogo'])->name('upload-logo-process');
});

// Debug
Route::get('/mikrotik-suite/netfusion/debug-connection', function (\App\Services\NetFusion\MikhmonAPI $api) {
    if (!session('router_session')) {
        return ['error' => 'No session logic in provider?'];
    }
    $p = $api->comm('/system/resource/print');
    return [
        'connected' => $api->connected,
        'port' => $api->port,
        'socket_is_resource' => is_resource($api->socket) ? 'yes' : 'no',
        'resource_raw' => $p,
        'resource_first' => $p[0] ?? null,
        'session_data' => session('router_session'),
    ];
});
