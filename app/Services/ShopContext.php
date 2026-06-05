<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Session;

class ShopContext
{
    /**
     * Resolve the currently active shop ID for the authenticated user context.
     * Enforces manager assignment and owner verification, with session caching.
     */
    public function getActiveShopId(): ?int
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        // Managers are strictly bound to their assigned shop
        if ($user->isManager()) {
            return $user->shop_id;
        }

        // Check if there is an explicit shop_id in the request
        $shopId = request()->route('shop_id') 
            ?? request()->input('shop_id');

        // Check if we are viewing a specific shop route (e.g. shops/{id})
        if (!$shopId && request()->routeIs('shop.*')) {
            $shopId = request()->route('shop') ?? request()->route('id');
        }

        if ($shopId && is_numeric($shopId)) {
            $shopId = (int) $shopId;
            // Verify ownership before caching
            if ($user->ownsShop($shopId)) {
                Session::put('current_shop_id', $shopId);
                return $shopId;
            }
        }

        // Fallback to session
        $sessionShopId = Session::get('current_shop_id');
        if ($sessionShopId && $user->ownsShop((int) $sessionShopId)) {
            return (int) $sessionShopId;
        }

        // Fallback to first owned shop
        $firstShopId = $user->shops()->value('id');
        if ($firstShopId) {
            Session::put('current_shop_id', (int) $firstShopId);
            return (int) $firstShopId;
        }

        return null;
    }
}
