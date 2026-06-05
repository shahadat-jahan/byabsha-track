<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\DashboardController;

Route::middleware(['auth', 'module.access:dashboard'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::post('/select-shop', [DashboardController::class, 'selectShop'])->name('select-shop');
    Route::get('/shop-details/{shop}', [DashboardController::class, 'shopDetails'])->name('shop-details');
});
