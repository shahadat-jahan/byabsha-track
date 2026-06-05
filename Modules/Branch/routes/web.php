<?php

use Illuminate\Support\Facades\Route;
use Modules\Branch\Http\Controllers\BranchController;

Route::middleware(['auth', 'module.access:branch'])->prefix('branches')->name('branch.')->group(function () {
    Route::get('/', [BranchController::class, 'index'])->name('index');
    Route::get('/create', [BranchController::class, 'create'])->name('create');
    Route::post('/', [BranchController::class, 'store'])->name('store');
    Route::get('/{id}', [BranchController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [BranchController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BranchController::class, 'update'])->name('update');
    Route::delete('/{id}', [BranchController::class, 'destroy'])->name('destroy');
});
