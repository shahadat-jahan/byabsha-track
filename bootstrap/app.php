<?php

use App\Http\Middleware\CheckModuleAccess;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureShopOwnership;
use App\Http\Middleware\EnsureSubscriptionActive;
use App\Http\Middleware\RestrictDuringMaintenanceMode;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\ValidateSubscription;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            SetLocale::class,
            RestrictDuringMaintenanceMode::class,
            EnsureSubscriptionActive::class,
        ]);

        $middleware->alias([
            'role' => CheckRole::class,
            'module.access' => CheckModuleAccess::class,
            'maintenance.restrict' => RestrictDuringMaintenanceMode::class,
            'shop.owner' => EnsureShopOwnership::class,
            'subscription.active' => EnsureSubscriptionActive::class,
            'subscription.validate' => ValidateSubscription::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
