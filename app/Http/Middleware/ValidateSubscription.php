<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ShopContext;
use Modules\Shop\Models\Shop;

class ValidateSubscription
{
    /**
     * Routes that are always accessible regardless of subscription status.
     *
     * @var array<string>
     */
    private const ALWAYS_ALLOWED_ROUTE_NAMES = [
        // Authentication
        'login', 'logout', 'password.request', 'password.email',
        'password.reset', 'password.store', 'password.update',
        // Subscription Billing & Management
        'subscription.plans', 'subscription.my', 'subscription.payment.submit',
        // User Profile Configuration
        'user.profile.edit', 'user.profile.update', 'user.password.update',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $moduleKey
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ?string $moduleKey = null): Response
    {
        $user = $request->user();

        // 1. Unauthenticated users are bypassed here (handled by auth middleware)
        if (!$user) {
            return $next($request);
        }

        // 2. Superadmins always bypass all subscription limits
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // 3. Admin review and configuration routes bypass subscription validations
        if ($request->is('admin/*')) {
            return $next($request);
        }

        // 4. White-listed routes are always accessible
        $routeName = $request->route()?->getName();
        if ($routeName && in_array($routeName, self::ALWAYS_ALLOWED_ROUTE_NAMES, true)) {
            return $next($request);
        }

        // 5. Resolve the active shop context
        $shopId = app(ShopContext::class)->getActiveShopId();
        if (!$shopId) {
            // Permit setup/onboarding screens if no shop exists yet
            return $next($request);
        }

        $shop = Shop::find($shopId);
        if (!$shop) {
            return $next($request);
        }

        // 6. Check subscription existence and validity
        $subscription = $shop->activeSubscription;

        // If the subscription is inactive or expired and not in grace period
        if (!$subscription || (!$subscription->isActive() && !$subscription->inGracePeriod())) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('Your subscription has expired or is inactive. Please renew to continue.'),
                    'redirect' => route('subscription.plans')
                ], 402);
            }

            return redirect()->route('subscription.plans')
                ->with('error', __('Your subscription has expired. Please buy a subscription to continue.'));
        }

        // 7. Verify module-specific permissions
        if ($moduleKey) {
            // Manager inherits the shop owner's subscription permissions, but must satisfy their granular role overrides
            if ($user->isManager()) {
                if (!in_array($moduleKey, $user->getModuleAccessList(), true)) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => __('You do not have permission to access this module.')
                        ], 403);
                    }
                    return response()->view('subscription::subscription-required', ['module' => $moduleKey], 403);
                }
            }

            // Verify if the active plan actually includes this module
            if (!$subscription->plan || !$subscription->plan->hasModule($moduleKey)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => __('This module is not included in your active subscription plan. Please upgrade.'),
                    ], 402);
                }

                return response()->view('subscription::subscription-required', ['module' => $moduleKey], 402);
            }
        }

        return $next($request);
    }
}
