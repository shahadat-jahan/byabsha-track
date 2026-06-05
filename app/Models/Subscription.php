<?php

namespace App\Models;

class Subscription
{
    /**
     * Return available plans as simple arrays for the admin UI.
     * Premium includes all features; Basic and Standard have limited features.
     *
     * @return array
     */
    public static function plans(): array
    {
        // Load plans from DB (subscription_plans table) and return a simple array representation
        $models = \Modules\Subscription\Models\SubscriptionPlan::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return $models->map(function ($plan) {
            return [
                'key' => $plan->slug,
                'name' => $plan->name,
                'price' => $plan->isFree() ? 'Free' : ($plan->price . ' / ' . $plan->billing_cycle),
                'description' => $plan->description,
                'features' => $plan->features ?? [],
                'limits' => $plan->limits ?? [],
                'is_trial' => $plan->is_trial ?? false,
            ];
        })->toArray();
    }

    /**
     * Get plan limits for feature enforcement.
     * Returns limits based on subscription plan.
     */
    public static function getPlanLimits(string $planKey = 'basic'): array
    {
        $plan = \Modules\Subscription\Models\SubscriptionPlan::where('slug', $planKey)->first();
        if (!$plan) {
            // fallback to basic DB plan or default basic limits
            $plan = \Modules\Subscription\Models\SubscriptionPlan::where('slug', 'basic')->first() ?: \Modules\Subscription\Models\SubscriptionPlan::freePlan();
        }

        // Normalize limits: prefer JSON 'limits' if present, otherwise map legacy columns
        $limits = (array) ($plan->limits ?? []);
        $legacy = [
            'max_shops' => $plan->max_shops ?? null,
            'max_branches' => $plan->max_branches ?? null,
            'max_brands' => $plan->max_brands ?? null,
            'max_categories' => $plan->max_categories ?? null,
            'max_sales' => $plan->max_sales ?? null,
        ];

        return array_merge($legacy, $limits);
    }

    /**
     * Get feature limits for a specific plan.
     */
    public static function getFeatureLimits(string $feature, string $planKey = 'basic'): mixed
    {
        $plan = \Modules\Subscription\Models\SubscriptionPlan::where('slug', $planKey)->first();
        if (!$plan) {
            $plan = \Modules\Subscription\Models\SubscriptionPlan::where('slug', 'basic')->first() ?: \Modules\Subscription\Models\SubscriptionPlan::freePlan();
        }

        // try features JSON first
        $limits = (array) ($plan->limits ?? []);
        if (array_key_exists($feature, $limits)) {
            return $limits[$feature];
        }

        // legacy: try direct column mapping
        $map = [
            'max_shops' => 'max_shops',
            'max_products' => null,
            'max_sales_records' => 'max_sales',
        ];

        if (isset($map[$feature]) && $map[$feature] && property_exists($plan, $map[$feature])) {
            return $plan->{$map[$feature]};
        }

        return null;
    }

    /**
     * Check if a feature is available for a plan.
     */
    public static function isFeatureAvailable(string $feature, string $planKey = 'basic'): bool
    {
        $plan = \Modules\Subscription\Models\SubscriptionPlan::where('slug', $planKey)->first();
        if (!$plan) {
            $plan = \Modules\Subscription\Models\SubscriptionPlan::where('slug', 'basic')->first() ?: \Modules\Subscription\Models\SubscriptionPlan::freePlan();
        }

        // features JSON
        $features = (array) ($plan->features ?? []);
        if (array_key_exists($feature, $features)) {
            return (bool) $features[$feature];
        }

        // legacy columns
        $legacyMap = [
            'capitals' => 'has_capital',
            'restock' => 'has_restock',
            'reports' => 'has_reports',
        ];

        if (isset($legacyMap[$feature]) && property_exists($plan, $legacyMap[$feature])) {
            return (bool) $plan->{$legacyMap[$feature]};
        }

        return false;
    }
}
