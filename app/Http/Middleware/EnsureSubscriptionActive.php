<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionActive
{
    /**
     * Routes that are always accessible regardless of subscription status.
     */
    private const ALWAYS_ALLOWED_ROUTE_NAMES = [
        // Auth
        'login', 'logout', 'password.request', 'password.email',
        'password.reset', 'password.store', 'password.update',
        // Subscription management (so expired users can pay)
        'subscription.plans', 'subscription.my', 'subscription.payment.submit',
        // User profile
        'user.profile.edit', 'user.profile.update', 'user.password.update',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        // Superadmin is never blocked.
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Admin routes are handled separately.
        if ($request->is('admin/*')) {
            return $next($request);
        }

        // Always allow the whitelisted routes.
        $routeName = $request->route()?->getName();
        if ($routeName && in_array($routeName, self::ALWAYS_ALLOWED_ROUTE_NAMES, true)) {
            return $next($request);
        }

        // If trial has expired and no active paid subscription, block access.
        if ($user->isTrialExpired()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your subscription has expired. Please subscribe to continue.',
                ], 402);
            }

            return response()->view('subscription::expired', [
                'user' => $user,
                'trialEnded' => $user->trialEndsAt(),
            ], 402);
        }

        return $next($request);
    }
}
