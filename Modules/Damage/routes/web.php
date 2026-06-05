<?php

use Illuminate\Support\Facades\Route;
use Modules\Damage\Http\Controllers\DamageController;

Route::middleware(['auth', 'module.access:damage'])->prefix('damages')->name('damage.')->group(function () {
    Route::get('/', [DamageController::class, 'index'])->name('index');
    Route::get('/table', [DamageController::class, 'damagesTable'])->name('table');
    Route::get('/create', [DamageController::class, 'create'])->name('create');
    Route::get('/batches-by-shop', [DamageController::class, 'batchesByShop'])->name('batches-by-shop');
    Route::post('/', [DamageController::class, 'store'])->name('store');
    Route::get('/{id}', [DamageController::class, 'show'])->name('show');
    Route::delete('/{id}', [DamageController::class, 'destroy'])->name('destroy');
});
