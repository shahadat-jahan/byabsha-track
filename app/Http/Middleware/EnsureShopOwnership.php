<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureShopOwnership
{
    /**
     * Verify that the shop_id in the route or request belongs to the authenticated user.
     * Superadmin always passes through. Manager is checked against their assigned shop_id.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        $shopId = (int) ($request->route('id') ?? $request->input('shop_id'));

        if ($shopId && ! $user->ownsShop($shopId)) {
            abort(403, 'You do not have access to this shop.');
        }

        return $next($request);
    }
}
