<?php

use Illuminate\Support\Facades\Route;
use Modules\Brand\Http\Controllers\BrandController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('brands', BrandController::class)->names('brand');
});
