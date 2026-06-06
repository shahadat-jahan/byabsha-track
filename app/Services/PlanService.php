<?php

namespace App\Services;

use App\Models\User;
use Modules\Brand\Models\Brand;
use Modules\Category\Models\Category;
use Modules\Product\Models\Product;
use Modules\Sale\Models\Sale;
use Modules\Shop\Models\Shop;
use Modules\Subscription\Models\SubscriptionPlan;

class PlanService
{
    public function getPlanForUser(User $user): array
    {
        // Prefer the user's active subscription plan model
        try {
            $plan = $user->currentPlan();
            if ($plan instanceof SubscriptionPlan) {
                return $plan->toArray();
            }
        } catch (\Throwable $e) {
            // ignore and fallback
        }

        return $this->configPlanFallback('free');
    }

    protected function configPlanFallback(string $slug): array
    {
        $plans = config('subscription_plans.plans', []);

        return $plans[$slug] ?? $plans['free'] ?? [];
    }

    public function isFeatureEnabled(User $user, string $feature): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        $planModel = $user->currentPlan();
        if ($planModel instanceof SubscriptionPlan) {
            return (bool) $planModel->getFeature($feature, false);
        }

        // fallback: allow
        return true;
    }

    public function canCreate(User $user, string $resource): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        $planModel = $user->currentPlan();
        $limit = null;
        if ($planModel instanceof SubscriptionPlan) {
            $limit = $planModel->getLimit($resource);
        }

        if ($limit === null) {
            return true; // unlimited
        }

        switch ($resource) {
            case 'shops':
                $count = Shop::where('user_id', $user->id)->count();

                return $count < $limit;
            case 'brands':
                $count = Brand::where('user_id', $user->id)->count();

                return $count < $limit;
            case 'categories':
                $count = Category::where('user_id', $user->id)->count();

                return $count < $limit;
            case 'products':
                $shopIds = Shop::where('user_id', $user->id)->pluck('id')->toArray();
                $count = Product::whereIn('shop_id', $shopIds)->count();

                return $count < $limit;
            case 'sales':
                $shopIds = Shop::where('user_id', $user->id)->pluck('id')->toArray();
                $count = Sale::whereIn('shop_id', $shopIds)->count();

                return $count < $limit;
        }

        return true;
    }
}
