<?php

use Illuminate\Support\Facades\Route;
use Modules\Restock\Http\Controllers\RestockController;

Route::middleware(['auth', 'module.access:restock'])->prefix('restocks')->name('restock.')->group(function () {
    Route::get('/', [RestockController::class, 'index'])->name('index');
    Route::get('/table', [RestockController::class, 'restocksTable'])->name('table');
    Route::get('/create', [RestockController::class, 'create'])->name('create');
    Route::post('/', [RestockController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [RestockController::class, 'edit'])->name('edit');
    Route::put('/{id}', [RestockController::class, 'update'])->name('update');
    Route::delete('/{id}', [RestockController::class, 'destroy'])->name('destroy');
    Route::get('/products-by-shop', [RestockController::class, 'productsByShop'])->name('products-by-shop');
});
