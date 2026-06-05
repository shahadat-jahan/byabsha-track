<?php

use Illuminate\Support\Facades\Route;
use Modules\Landing\Http\Controllers\LandingController;

Route::get('/', [LandingController::class, 'index'])->name('landing.index');

