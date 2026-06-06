<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// Language switcher
Route::get('/language/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch')
    ->where('locale', 'en|bn');

// Notifications (authenticated users only)
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::get('/recent', [NotificationController::class, 'recent'])->name('recent');
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    Route::delete('/read/all', [NotificationController::class, 'deleteAllRead'])->name('delete-all-read');
});

// Root handled by Landing module
