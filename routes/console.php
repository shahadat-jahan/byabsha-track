<?php

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Modules\Product\Models\Product;
use Modules\Sale\Models\Sale;
use Modules\Settings\Models\Setting;
use Modules\Shop\Models\Shop;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('notifications:check-low-stock', function () {
    $threshold = (int) Setting::get('low_stock_alert', 10);
    $threshold = $threshold > 0 ? $threshold : 10;

    $lowStockProducts = Product::with('shop')
        ->where('stock_quantity', '<=', $threshold)
        ->orderBy('stock_quantity')
        ->get();

    if ($lowStockProducts->isEmpty()) {
        $this->info('No low stock products found.');

        return;
    }

    $users = User::query()->get();
    $createdCount = 0;

    foreach ($users as $user) {
        foreach ($lowStockProducts as $product) {
            $alreadyNotified = Notification::query()
                ->where('user_id', $user->id)
                ->where('type', 'low_stock')
                ->whereDate('created_at', today())
                ->where('data->product_id', $product->id)
                ->exists();

            if ($alreadyNotified) {
                continue;
            }

            Notification::create([
                'user_id' => $user->id,
                'type' => 'low_stock',
                'title' => __('notifications.type_low_stock'),
                'message' => __('notifications.low_stock_message', [
                    'product' => $product->name,
                    'quantity' => $product->stock_quantity,
                ]),
                'data' => [
                    'product_id' => $product->id,
                    'shop_id' => $product->shop_id,
                    'shop_name' => $product->shop?->name,
                    'threshold' => $threshold,
                    'url' => route('stock.index', ['shop_id' => $product->shop_id]),
                ],
            ]);

            $createdCount++;
        }
    }

    $this->info("Low-stock notifications created: {$createdCount}");
})->purpose('Create low stock notifications for all users');

Artisan::command('notifications:daily-summary', function () {
    $today = today();

    $shopsSummary = Shop::query()
        ->withCount('sales')
        ->get()
        ->map(function ($shop) use ($today) {
            $sales = Sale::query()
                ->where('shop_id', $shop->id)
                ->whereDate('sale_date', $today)
                ->get();

            return [
                'shop_id' => $shop->id,
                'shop_name' => $shop->name,
                'transactions' => $sales->count(),
                'revenue' => (float) $sales->sum('total_amount'),
                'profit' => (float) $sales->sum('profit'),
            ];
        });

    $totalTransactions = (int) $shopsSummary->sum('transactions');
    $totalRevenue = (float) $shopsSummary->sum('revenue');
    $totalProfit = (float) $shopsSummary->sum('profit');

    $users = User::query()->get();
    $createdCount = 0;

    foreach ($users as $user) {
        $alreadyNotified = Notification::query()
            ->where('user_id', $user->id)
            ->where('type', 'daily_summary')
            ->whereDate('created_at', $today)
            ->exists();

        if ($alreadyNotified) {
            continue;
        }

        Notification::create([
            'user_id' => $user->id,
            'type' => 'daily_summary',
            'title' => __('notifications.type_daily_summary'),
            'message' => __('notifications.daily_summary_message').
                " (Txn: {$totalTransactions}, Revenue: ".number_format($totalRevenue, 2).", Profit: ".number_format($totalProfit, 2).')',
            'data' => [
                'date' => $today->toDateString(),
                'total_transactions' => $totalTransactions,
                'total_revenue' => $totalRevenue,
                'total_profit' => $totalProfit,
                'shops' => $shopsSummary,
                'url' => route('report.daily', ['month' => $today->format('Y-m')]),
            ],
        ]);

        $createdCount++;
    }

    $this->info("Daily summary notifications created: {$createdCount}");
})->purpose('Create daily summary notifications for all users');

Schedule::command('notifications:check-low-stock')->dailyAt('09:00');
Schedule::command('notifications:daily-summary')->dailyAt('21:00');

Artisan::command('subscription:check-expiry', function () {
    $now = now();
    $subscriptions = \Modules\Subscription\Models\Subscription::where('status', 'active')
        ->whereNotNull('ends_at')
        ->where('ends_at', '<=', $now)
        ->with(['user', 'plan'])
        ->get();

    $expiredCount = 0;
    foreach ($subscriptions as $sub) {
        $sub->update(['status' => 'expired']);

        \App\Models\Notification::create([
            'user_id' => $sub->user_id,
            'type'    => 'subscription_expired',
            'title'   => 'Subscription Expired',
            'message' => "Your subscription to the {$sub->plan->name} plan has expired. Please buy a subscription to restore access.",
            'data'    => [
                'plan_slug' => $sub->plan->slug,
                'url'       => route('subscription.plans')
            ],
        ]);

        $expiredCount++;
    }

    $this->info("Successfully expired {$expiredCount} subscription(s).");
})->purpose('Find and expire all active subscriptions that have passed their ends_at date');

Schedule::command('subscription:check-expiry')->hourly();
