<?php

use Illuminate\Support\Facades\Route;
use Modules\Report\Http\Controllers\ReportController;

Route::middleware(['auth', 'module.access:report'])->prefix('reports')->name('report.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/print', [ReportController::class, 'printIndex'])->name('print.index');
    Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
    Route::get('/products', [ReportController::class, 'products'])->name('products');
    Route::get('/shops', [ReportController::class, 'shops'])->name('shops');
    Route::get('/daily', [ReportController::class, 'daily'])->name('daily');
    Route::get('/monthly', [ReportController::class, 'monthly'])->name('monthly');

    // PDF Export
    Route::get('/export/daily-pdf', [ReportController::class, 'exportDailyPdf'])->name('export.daily-pdf');
    Route::get('/export/monthly-pdf', [ReportController::class, 'exportMonthlyPdf'])->name('export.monthly-pdf');
    Route::get('/export/sales-pdf', [ReportController::class, 'exportSalesPdf'])->name('export.sales-pdf');
    Route::get('/export/products-pdf', [ReportController::class, 'exportProductsPdf'])->name('export.products-pdf');
    Route::get('/export/shops-pdf', [ReportController::class, 'exportShopsPdf'])->name('export.shops-pdf');
});
