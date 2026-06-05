<?php

use Illuminate\Support\Facades\Route;
use Modules\Restock\Http\Controllers\RestockController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('restocks', RestockController::class)->names('restock');
});
