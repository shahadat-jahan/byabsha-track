<?php

use Illuminate\Support\Facades\Route;
use Modules\Shop\Http\Controllers\ShopController;

Route::middleware(['auth', 'module.access:shop'])->prefix('shops')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('/create', [ShopController::class, 'create'])->name('create');
    Route::post('/', [ShopController::class, 'store'])->name('store');
    Route::get('/{id}', [ShopController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [ShopController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ShopController::class, 'update'])->name('update');
    Route::delete('/{id}', [ShopController::class, 'destroy'])->name('destroy');
});
