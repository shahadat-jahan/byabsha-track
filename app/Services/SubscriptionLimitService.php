<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\User;
use Modules\Product\Models\Product;
use Modules\Sale\Models\Sale;
use Modules\Shop\Models\Shop;

class SubscriptionLimitService
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Check if user has reached shop creation limit.
     */
    public function hasReachedShopLimit(): bool
    {
        if ($this->user->isSuperAdmin()) {
            return false;
        }

        $maxShops = $this->user->getFeatureLimit('max_shops');
        $currentShops = $this->user->shops()->count();

        return $currentShops >= $maxShops;
    }

    /**
     * Get remaining shop creation quota.
     */
    public function getRemainingShopQuota(): int
    {
        if ($this->user->isSuperAdmin()) {
            return 999;
        }

        $maxShops = $this->user->getFeatureLimit('max_shops');
        $currentShops = $this->user->shops()->count();

        return max(0, $maxShops - $currentShops);
    }

    /**
     * Check if user can create more shops.
     */
    public function canCreateShop(): bool
    {
        return ! $this->hasReachedShopLimit();
    }

    /**
     * Check if user has reached product creation limit for a shop.
     */
    public function hasReachedProductLimit(?int $shopId = null): bool
    {
        if ($this->user->isSuperAdmin()) {
            return false;
        }

        $maxProducts = $this->user->getFeatureLimit('max_products');

        if ($shopId) {
            $currentProducts = Product::where('shop_id', $shopId)
                ->where('deleted_at', null)
                ->count();
        } else {
            $currentProducts = Product::whereIn('shop_id', $this->user->getShopIds())
                ->where('deleted_at', null)
                ->count();
        }

        return $currentProducts >= $maxProducts;
    }

    /**
     * Get remaining product creation quota.
     */
    public function getRemainingProductQuota(): int
    {
        if ($this->user->isSuperAdmin()) {
            return 999;
        }

        $maxProducts = $this->user->getFeatureLimit('max_products');
        $currentProducts = Product::whereIn('shop_id', $this->user->getShopIds())
            ->where('deleted_at', null)
            ->count();

        return max(0, $maxProducts - $currentProducts);
    }

    /**
     * Check if user can create more products.
     */
    public function canCreateProduct(?int $shopId = null): bool
    {
        return ! $this->hasReachedProductLimit($shopId);
    }

    /**
     * Check if user has reached sales record limit.
     */
    public function hasReachedSalesLimit(): bool
    {
        if ($this->user->isSuperAdmin()) {
            return false;
        }

        $maxSales = $this->user->getFeatureLimit('max_sales_records');
        $currentSales = Sale::whereIn('shop_id', $this->user->getShopIds())
            ->where('deleted_at', null)
            ->count();

        return $currentSales >= $maxSales;
    }

    /**
     * Get remaining sales record quota.
     */
    public function getRemainingSalesQuota(): int
    {
        if ($this->user->isSuperAdmin()) {
            return 999;
        }

        $maxSales = $this->user->getFeatureLimit('max_sales_records');
        $currentSales = Sale::whereIn('shop_id', $this->user->getShopIds())
            ->where('deleted_at', null)
            ->count();

        return max(0, $maxSales - $currentSales);
    }

    /**
     * Check if user can create more sales records.
     */
    public function canCreateSale(): bool
    {
        return ! $this->hasReachedSalesLimit();
    }

    /**
     * Get usage percentage for a feature.
     */
    public function getUsagePercentage(string $feature): int
    {
        if ($this->user->isSuperAdmin()) {
            return 0;
        }

        $limit = $this->user->getFeatureLimit($feature);

        if ($limit <= 0) {
            return 0;
        }

        $usage = match ($feature) {
            'max_shops' => $this->user->shops()->count(),
            'max_products' => Product::whereIn('shop_id', $this->user->getShopIds())
                ->where('deleted_at', null)
                ->count(),
            'max_sales_records' => Sale::whereIn('shop_id', $this->user->getShopIds())
                ->where('deleted_at', null)
                ->count(),
            default => 0
        };

        return min(100, (int) (($usage / $limit) * 100));
    }

    /**
     * Get subscription plan info array for display.
     */
    public function getSubscriptionInfo(): array
    {
        $planKey = $this->user->getCurrentPlanKey();
        $limits = $this->user->getPlanLimits();

        return [
            'plan' => $planKey,
            'limits' => $limits,
            'usage' => [
                'shops' => [
                    'current' => $this->user->shops()->count(),
                    'max' => $limits['max_shops'],
                    'percentage' => $this->getUsagePercentage('max_shops'),
                ],
                'products' => [
                    'current' => Product::whereIn('shop_id', $this->user->getShopIds())
                        ->where('deleted_at', null)
                        ->count(),
                    'max' => $limits['max_products'],
                    'percentage' => $this->getUsagePercentage('max_products'),
                ],
                'sales' => [
                    'current' => Sale::whereIn('shop_id', $this->user->getShopIds())
                        ->where('deleted_at', null)
                        ->count(),
                    'max' => $limits['max_sales_records'],
                    'percentage' => $this->getUsagePercentage('max_sales_records'),
                ],
            ],
        ];
    }
}
