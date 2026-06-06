<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Settings\Models\Setting;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RestrictDuringMaintenanceMode
{
    /**
     * Block owner/user access when app maintenance mode is enabled in settings.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->runningInConsole() && ! app()->runningUnitTests()) {
            return $next($request);
        }

        try {
            $maintenanceMode = (bool) Setting::get('maintenance_mode', false);

            if (! $maintenanceMode) {
                return $next($request);
            }
        } catch (Throwable) {
            return $next($request);
        }

        // 1. Always allow Super Admins to bypass maintenance mode.
        $user = $request->user();
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }

        // 2. Whitelist essential authentication and locale routes
        if ($request->routeIs('login', 'login.submit', 'register', 'register.submit', 'logout', 'password.*', 'language.switch')) {
            return $next($request);
        }

        // 3. Whitelist landing page with auth query parameters (so login/register modals can load)
        if ($request->routeIs('landing.index') && in_array($request->query('auth'), ['login', 'register'], true)) {
            return $next($request);
        }

        // 4. Whitelist essential paths and assets
        if ($request->is('up', '_tailwind*', 'livewire*', 'storage/*', 'sanctum/*')) {
            return $next($request);
        }

        // 5. Otherwise, return the maintenance page view with 503 status code
        return response()->view('maintenance.role-maintenance', [], 503);
    }
}
