<?php

namespace Modules\Subscription\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Subscription\Models\SubscriptionPlan;

class SubscriptionDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free Trial', 'slug' => 'free', 'price' => 0,
                'billing_cycle' => 'monthly',
                'description' => '1-month free trial for new users. Includes basic features to get started.',
                'max_shops' => null, 'max_branches' => null, 'max_brands' => null,
                'max_categories' => null, 'max_sales' => null,
                'has_capital' => false, 'has_restock' => false,
                'has_reports' => false, 'has_priority_support' => false,
                'is_trial' => true, 'trial_days' => 30,
                'is_active' => true, 'sort_order' => 0,
            ],
            [
                'name' => 'Starter', 'slug' => 'starter', 'price' => 1000,
                'billing_cycle' => 'monthly',
                'description' => 'Ideal for small businesses managing a few shops with full feature access.',
                'max_shops' => null, 'max_branches' => null, 'max_brands' => null,
                'max_categories' => null, 'max_sales' => null,
                'has_capital' => true, 'has_restock' => true,
                'has_reports' => true, 'has_priority_support' => false,
                'is_active' => true, 'sort_order' => 1,
            ],
            [
                'name' => 'Growth', 'slug' => 'growth', 'price' => 2500,
                'billing_cycle' => 'monthly',
                'description' => 'Built for growing businesses that need more capacity and deeper insights.',
                'max_shops' => null, 'max_branches' => null, 'max_brands' => null,
                'max_categories' => null, 'max_sales' => null,
                'has_capital' => true, 'has_restock' => true,
                'has_reports' => true, 'has_priority_support' => false,
                'is_active' => true, 'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise', 'slug' => 'enterprise', 'price' => 5000,
                'billing_cycle' => 'monthly',
                'description' => 'Unlimited everything. Maximum power for large-scale operations.',
                'max_shops' => null, 'max_branches' => null, 'max_brands' => null,
                'max_categories' => null, 'max_sales' => null,
                'has_capital' => true, 'has_restock' => true,
                'has_reports' => true, 'has_priority_support' => true,
                'is_active' => true, 'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
