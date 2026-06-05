<?php

use Illuminate\Support\Facades\Route;
use Modules\Subscription\Http\Controllers\SubscriptionController;
use Modules\Subscription\Http\Controllers\Admin\AdminSubscriptionController;

Route::middleware(['web', 'auth'])->group(function () {

    Route::middleware('role:owner,manager')
        ->prefix('subscription')
        ->name('subscription.')
        ->group(function () {
            Route::get('/plans', [SubscriptionController::class, 'plans'])->name('plans');
            Route::get('/my', [SubscriptionController::class, 'mySubscription'])->name('my');
            Route::post('/payment', [SubscriptionController::class, 'submitPayment'])->middleware('role:owner')->name('payment.submit');
        });

    Route::middleware(['role:superadmin'])
        ->prefix('admin/subscriptions')
        ->name('admin.subscriptions.')
        ->group(function () {
            Route::get('/', [AdminSubscriptionController::class, 'index'])->name('index');
            Route::get('/active', [AdminSubscriptionController::class, 'active'])->name('active');
            Route::prefix('plans')->name('plans.')->group(function () {
                Route::get('/', [\Modules\Subscription\Http\Controllers\Admin\PlanController::class, 'index'])->name('index');
                Route::get('/create', [\Modules\Subscription\Http\Controllers\Admin\PlanController::class, 'create'])->name('create');
                Route::post('/', [\Modules\Subscription\Http\Controllers\Admin\PlanController::class, 'store'])->name('store');
                Route::get('/{plan}/edit', [\Modules\Subscription\Http\Controllers\Admin\PlanController::class, 'edit'])->name('edit');
                Route::put('/{plan}', [\Modules\Subscription\Http\Controllers\Admin\PlanController::class, 'update'])->name('update');
                Route::delete('/{plan}', [\Modules\Subscription\Http\Controllers\Admin\PlanController::class, 'destroy'])->name('destroy');
            });
            Route::get('/{paymentRequest}', [AdminSubscriptionController::class, 'show'])->whereNumber('paymentRequest')->name('show');
            Route::post('/{paymentRequest}/approve', [AdminSubscriptionController::class, 'approve'])->whereNumber('paymentRequest')->name('approve');
            Route::post('/{paymentRequest}/reject', [AdminSubscriptionController::class, 'reject'])->whereNumber('paymentRequest')->name('reject');
        });
});
