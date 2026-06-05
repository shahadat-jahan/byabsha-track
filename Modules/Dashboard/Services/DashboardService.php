<?php

namespace Modules\Dashboard\Services;

use App\Models\User;
use Modules\Shop\Models\Shop;
use Modules\Product\Models\Product;
use Modules\Sale\Models\Sale;
use Modules\Capital\Models\Capital;
use Modules\Capital\Services\CapitalService;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    protected $capitalService;

    public function __construct(CapitalService $capitalService)
    {
        $this->capitalService = $capitalService;
    }

    public function getShopMetrics(User $user)
    {
        $shops = Shop::forUser($user)->get();
        $metrics = [];

        foreach ($shops as $shop) {
            $metrics[] = [
                'shop' => $shop,
                'today_sales' => $this->getTodaySales($shop->id),
                'today_profit' => $this->getTodayProfit($shop->id),
                'monthly_profit' => $this->getMonthlyProfit($shop->id),
                'total_capital' => $this->getTotalCapital($shop->id),
            ];
        }

        return $metrics;
    }

    public function getTodaySales($shopId)
    {
        return Sale::where('shop_id', $shopId)
            ->whereDate('sale_date', today())
            ->sum('total_amount');
    }

    public function getTodayProfit($shopId)
    {
        return Sale::where('shop_id', $shopId)
            ->whereDate('sale_date', today())
            ->sum('profit');
    }

    public function getMonthlyProfit($shopId)
    {
        return Sale::where('shop_id', $shopId)
            ->whereYear('sale_date', now()->year)
            ->whereMonth('sale_date', now()->month)
            ->sum('profit');
    }

    public function getTotalCapital($shopId)
    {
        $capital = Capital::where('shop_id', $shopId)->first();

        if (!$capital) {
            $this->capitalService->updateShopCapital($shopId);
            $capital = Capital::where('shop_id', $shopId)->first();
        }

        return $capital ? $capital->total_capital : 0;
    }

    public function getOverallMetrics(User $user)
    {
        $shopIds = $user->accessibleShopIds();

        return [
            'total_shops' => count($shopIds),
            'total_products' => Product::whereIn('shop_id', $shopIds)->count(),
            'total_sales_today' => Sale::whereIn('shop_id', $shopIds)->whereDate('sale_date', today())->count(),
            'total_revenue_today' => Sale::whereIn('shop_id', $shopIds)->whereDate('sale_date', today())->sum('total_amount'),
            'total_profit_today' => Sale::whereIn('shop_id', $shopIds)->whereDate('sale_date', today())->sum('profit'),
            'low_stock_count' => Product::whereIn('shop_id', $shopIds)->where('stock_quantity', '<=', (int) \Modules\Settings\Models\Setting::get('low_stock_alert', 5))->count(),
        ];
    }
}
