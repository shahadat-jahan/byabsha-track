<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
});

Route::middleware(['auth', 'role:superadmin'])->prefix('users')->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/table', [UserController::class, 'usersTable'])->name('table');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{id}', [UserController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('update');
    Route::post('/{id}/deactivate', [UserController::class, 'deactivate'])->name('deactivate');
    Route::post('/{id}/activate', [UserController::class, 'activate'])->name('activate');

    Route::get('/{id}/approve', [UserController::class, 'approveForm'])->name('approve.form');
    Route::post('/{id}/approve', [UserController::class, 'approve'])->name('approve');

    Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/restore', [UserController::class, 'restore'])->name('restore');
});
