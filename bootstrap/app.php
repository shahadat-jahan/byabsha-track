<?php

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
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\RestrictDuringMaintenanceMode::class,
            \App\Http\Middleware\EnsureSubscriptionActive::class,
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'module.access' => \App\Http\Middleware\CheckModuleAccess::class,
            'maintenance.restrict' => \App\Http\Middleware\RestrictDuringMaintenanceMode::class,
            'shop.owner' => \App\Http\Middleware\EnsureShopOwnership::class,
            'subscription.active' => \App\Http\Middleware\EnsureSubscriptionActive::class,
            'subscription.validate' => \App\Http\Middleware\ValidateSubscription::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
