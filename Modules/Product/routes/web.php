<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;
use Modules\Product\Http\Controllers\ProductDynamicFieldController;

Route::middleware(['auth', 'module.access:product'])->prefix('products')->name('product.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');

    Route::prefix('dynamic-fields')->name('dynamic-fields.')->group(function () {
        Route::get('/', [ProductDynamicFieldController::class, 'index'])->name('index');
        Route::get('/create', [ProductDynamicFieldController::class, 'create'])->name('create');
        Route::post('/', [ProductDynamicFieldController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ProductDynamicFieldController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductDynamicFieldController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductDynamicFieldController::class, 'destroy'])->name('destroy');
    });

    Route::get('/{id}', [ProductController::class, 'show'])->name('show');
    Route::get('/{id}/batches', [ProductController::class, 'batches'])->name('batches');
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
});
