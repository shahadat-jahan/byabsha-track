<?php

use Illuminate\Support\Facades\Route;
use Modules\Sale\Http\Controllers\ExchangeController;
use Modules\Sale\Http\Controllers\SaleController;
use Modules\Sale\Http\Controllers\WarrantyController;

Route::middleware(['auth', 'module.access:sale'])->prefix('sales')->name('sale.')->group(function () {
    Route::get('/', [SaleController::class, 'index'])->name('index');
    Route::get('/products-table', [SaleController::class, 'productsTable'])->name('products-table');
    Route::get('/product/{product}/sales', [SaleController::class, 'productSales'])->name('product-sales');
    Route::post('/quick-sale', [SaleController::class, 'quickSale'])->name('quick-sale');
    Route::get('/create', [SaleController::class, 'create'])->name('create');
    Route::get('/products-by-shop', [SaleController::class, 'productsByShop'])->name('products-by-shop');
    Route::post('/', [SaleController::class, 'store'])->name('store');

    Route::prefix('warranties')->name('warranties.')->group(function () {
        Route::get('/', [WarrantyController::class, 'index'])->name('index');
        Route::get('/table', [WarrantyController::class, 'warrantiesTable'])->name('table');
        Route::get('/create', [WarrantyController::class, 'create'])->name('create');
        Route::post('/', [WarrantyController::class, 'store'])->name('store');
        Route::post('/{id}/claim', [WarrantyController::class, 'claim'])->name('claim');
        Route::post('/{id}/void', [WarrantyController::class, 'void'])->name('void');
    });

    Route::prefix('exchanges')->name('exchanges.')->group(function () {
        Route::get('/', [ExchangeController::class, 'index'])->name('index');
        Route::get('/table', [ExchangeController::class, 'exchangesTable'])->name('table');
        Route::get('/create', [ExchangeController::class, 'create'])->name('create');
        Route::post('/', [ExchangeController::class, 'store'])->name('store');
    });

    Route::get('/{id}', [SaleController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [SaleController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SaleController::class, 'update'])->name('update');
    Route::delete('/{id}', [SaleController::class, 'destroy'])->name('destroy');
});
