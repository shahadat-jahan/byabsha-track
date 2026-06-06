<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\SettingsController;

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');

        // Backward-compatibility redirects
        Route::get('/general', function () {
            return redirect()->route('settings.dashboard');
        });
        Route::get('/business', function () {
            return redirect()->route('settings.dashboard');
        });

        Route::get('/dashboard', [SettingsController::class, 'dashboard'])->name('dashboard');
        Route::get('/landing', [SettingsController::class, 'landing'])->name('landing');
        Route::get('/system', [SettingsController::class, 'system'])->name('system');

        Route::put('/{group?}', [SettingsController::class, 'update'])->name('update');
        Route::get('/clear-cache/{group?}', [SettingsController::class, 'clearCache'])->name('clear-cache');
    });
});
