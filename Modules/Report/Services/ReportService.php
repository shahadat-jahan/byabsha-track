<?php

namespace Modules\Report\Services;

use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use Modules\Sale\Models\Sale;
use Modules\Sale\Models\SaleExchange;
use Modules\Sale\Models\SaleWarranty;
use Modules\Settings\Models\Setting;
use Modules\Shop\Models\Shop;

class ReportService
{
    public function getSalesReport($filters = [])
    {
        $query = Sale::with(['shop', 'product']);

        if (! empty($filters['shop_ids'])) {
            $query->whereIn('shop_id', $filters['shop_ids']);
        }

        if (! empty($filters['shop_id'])) {
            $query->where('shop_id', $filters['shop_id']);
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('sale_date', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('sale_date', '<=', $filters['end_date']);
        }

        $sales = $query->latest('sale_date')->get();

        return [
            'sales' => $sales,
            'total_sales' => $sales->count(),
            'total_amount' => $sales->sum('total_amount'),
            'total_profit' => $sales->sum('profit'),
            'average_profit_per_sale' => $sales->count() > 0 ? $sales->avg('profit') : 0,
        ];
    }

    public function getSalesSummary($filters = [])
    {
        $query = DB::table('sales');

        if (! empty($filters['shop_ids'])) {
            $query->whereIn('shop_id', $filters['shop_ids']);
        }

        if (! empty($filters['shop_id'])) {
            $query->where('shop_id', $filters['shop_id']);
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('sale_date', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('sale_date', '<=', $filters['end_date']);
        }

        $summary = $query->selectRaw('
            COUNT(*) as total_transactions,
            SUM(quantity) as total_quantity_sold,
            SUM(total_amount) as total_revenue,
            SUM(profit) as total_profit,
            AVG(total_amount) as average_sale_amount,
            AVG(profit) as average_profit
        ')->first();

        return $summary;
    }

    public function getWarrantyExchangeSummary($filters = []): array
    {
        $warrantyQuery = SaleWarranty::query();
        $exchangeQuery = SaleExchange::query();

        if (! empty($filters['shop_ids'])) {
            $warrantyQuery->whereIn('shop_id', $filters['shop_ids']);
            $exchangeQuery->whereIn('shop_id', $filters['shop_ids']);
        }

        if (! empty($filters['shop_id'])) {
            $warrantyQuery->where('shop_id', $filters['shop_id']);
            $exchangeQuery->where('shop_id', $filters['shop_id']);
        }

        if (! empty($filters['start_date'])) {
            $warrantyQuery->whereDate('start_date', '>=', $filters['start_date']);
            $exchangeQuery->whereDate('exchange_date', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $warrantyQuery->whereDate('start_date', '<=', $filters['end_date']);
            $exchangeQuery->whereDate('exchange_date', '<=', $filters['end_date']);
        }

        $activeWarranties = (clone $warrantyQuery)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now()->toDateString())
            ->count();

        $expiredWarranties = (clone $warrantyQuery)
            ->where(function ($query) {
                $query->where('status', 'expired')
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('status', 'active')
                            ->whereDate('end_date', '<', now()->toDateString());
                    });
            })
            ->count();

        return [
            'active_warranties' => $activeWarranties,
            'expired_warranties' => $expiredWarranties,
            'total_exchanges' => (clone $exchangeQuery)->count(),
            'exchange_quantity' => (int) ((clone $exchangeQuery)->sum('quantity') ?? 0),
        ];
    }

    public function getSalesByShop($filters = [])
    {
        $query = DB::table('sales')
            ->join('shops', 'sales.shop_id', '=', 'shops.id')
            ->select(
                'shops.id',
                'shops.name',
                DB::raw('COUNT(sales.id) as total_sales'),
                DB::raw('SUM(sales.quantity) as total_quantity'),
                DB::raw('SUM(sales.total_amount) as total_revenue'),
                DB::raw('SUM(sales.profit) as total_profit')
            )
            ->groupBy('shops.id', 'shops.name');

        if (! empty($filters['shop_ids'])) {
            $query->whereIn('sales.shop_id', $filters['shop_ids']);
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('sales.sale_date', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('sales.sale_date', '<=', $filters['end_date']);
        }

        return $query->get();
    }

    public function getTopSellingProducts($filters = [], $limit = 10)
    {
        $query = DB::table('sales')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->join('shops', 'sales.shop_id', '=', 'shops.id')
            ->select(
                'products.id',
                'products.name',
                'shops.name as shop_name',
                DB::raw('SUM(sales.quantity) as total_sold'),
                DB::raw('SUM(sales.total_amount) as total_revenue'),
                DB::raw('SUM(sales.profit) as total_profit')
            )
            ->groupBy('products.id', 'products.name', 'shops.name');

        if (! empty($filters['shop_id'])) {
            $query->where('sales.shop_id', $filters['shop_id']);
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('sales.sale_date', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('sales.sale_date', '<=', $filters['end_date']);
        }

        return $query->orderByDesc('total_sold')->limit($limit)->get();
    }

    public function getStockSummary($filters = [])
    {
        $query = Product::with('shop');

        if (! empty($filters['shop_ids'])) {
            $query->whereIn('shop_id', $filters['shop_ids']);
        }

        if (! empty($filters['shop_id'])) {
            $query->where('shop_id', $filters['shop_id']);
        }

        $products = $query->get();

        $totalProducts = $products->count();
        $totalStockValue = $products->sum(function ($product) {
            return $product->stock_quantity * $product->purchase_price;
        });
        $totalPotentialRevenue = $products->sum(function ($product) {
            return $product->stock_quantity * $product->sale_price;
        });
        $totalPotentialProfit = $totalPotentialRevenue - $totalStockValue;

        $lowStockProducts = $products->filter(function ($product) {
            return $product->stock_quantity <= (int) Setting::get('low_stock_alert', 5);
        })->count();

        $outOfStockProducts = $products->filter(function ($product) {
            return $product->stock_quantity == 0;
        })->count();

        return [
            'total_products' => $totalProducts,
            'total_stock_value' => $totalStockValue,
            'total_potential_revenue' => $totalPotentialRevenue,
            'total_potential_profit' => $totalPotentialProfit,
            'low_stock_count' => $lowStockProducts,
            'out_of_stock_count' => $outOfStockProducts,
            'products' => $products,
        ];
    }

    public function getShops(array $shopIds = [])
    {
        $query = Shop::query();
        if (! empty($shopIds)) {
            $query->whereIn('id', $shopIds);
        }

        return $query->get();
    }

    /**
     * Paginated sales report for the sales sub-page.
     */
    public function getPaginatedSales($filters = [], $perPage = 25)
    {
        $query = Sale::with(['shop', 'product']);

        if (! empty($filters['shop_ids'])) {
            $query->whereIn('shop_id', $filters['shop_ids']);
        }

        if (! empty($filters['shop_id'])) {
            $query->where('shop_id', $filters['shop_id']);
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('sale_date', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('sale_date', '<=', $filters['end_date']);
        }

        return $query->latest('sale_date')->paginate($perPage)->withQueryString();
    }

    /**
     * Product report with sales aggregates.
     */
    public function getProductReport($filters = [])
    {
        $query = Product::with('shop')
            ->leftJoin('sales', 'products.id', '=', 'sales.product_id')
            ->select(
                'products.*',
                DB::raw('COALESCE(SUM(sales.quantity), 0) as total_units_sold'),
                DB::raw('COALESCE(SUM(sales.total_amount), 0) as total_revenue')
            )
            ->groupBy('products.id');

        if (! empty($filters['shop_ids'])) {
            $query->whereIn('products.shop_id', $filters['shop_ids']);
        }

        if (! empty($filters['shop_id'])) {
            $query->where('products.shop_id', $filters['shop_id']);
        }

        return $query->orderBy('products.name')->get();
    }

    /**
     * Per-shop comparison report.
     */
    public function getShopComparison($filters = [])
    {
        $shopIdsFilter = $filters['shop_ids'] ?? [];
        $query = Shop::withCount('products');
        if (! empty($shopIdsFilter)) {
            $query->whereIn('id', $shopIdsFilter);
        }
        $shops = $query->get();

        $result = [];

        foreach ($shops as $shop) {
            $salesQuery = Sale::where('shop_id', $shop->id);

            if (! empty($filters['start_date'])) {
                $salesQuery->whereDate('sale_date', '>=', $filters['start_date']);
            }

            if (! empty($filters['end_date'])) {
                $salesQuery->whereDate('sale_date', '<=', $filters['end_date']);
            }

            $salesData = $salesQuery->selectRaw('
                COUNT(*) as total_sales,
                COALESCE(SUM(total_amount), 0) as total_revenue,
                COALESCE(SUM(profit), 0) as total_profit
            ')->first();

            $stockValue = Product::where('shop_id', $shop->id)
                ->selectRaw('COALESCE(SUM(stock_quantity * purchase_price), 0) as value')
                ->value('value');

            $totalRevenue = $salesData->total_revenue ?? 0;
            $totalProfit = $salesData->total_profit ?? 0;
            $profitMargin = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;

            $result[] = (object) [
                'id' => $shop->id,
                'name' => $shop->name,
                'total_products' => $shop->products_count,
                'stock_value' => $stockValue,
                'total_sales' => $salesData->total_sales ?? 0,
                'total_revenue' => $totalRevenue,
                'total_profit' => $totalProfit,
                'profit_margin' => $profitMargin,
            ];
        }

        return collect($result);
    }

    public function getDailySales($filters = [])
    {
        $query = DB::table('sales')
            ->select(
                DB::raw('DATE(sale_date) as date'),
                DB::raw('COUNT(*) as transactions'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('SUM(profit) as profit')
            )
            ->groupBy(DB::raw('DATE(sale_date)'))
            ->orderBy('date', 'desc');

        if (! empty($filters['shop_ids'])) {
            $query->whereIn('shop_id', $filters['shop_ids']);
        }

        if (! empty($filters['shop_id'])) {
            $query->where('shop_id', $filters['shop_id']);
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('sale_date', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('sale_date', '<=', $filters['end_date']);
        }

        return $query->limit(30)->get();
    }

    /**
     * Detailed sales rows grouped by date for the report index modal.
     */
    public function getSalesDetailsForDates($filters = [], $dates = [])
    {
        if (empty($dates)) {
            return collect();
        }

        $query = Sale::with(['shop:id,name', 'product:id,name'])
            ->whereIn(DB::raw('DATE(sale_date)'), $dates)
            ->orderBy('sale_date', 'desc')
            ->orderBy('id', 'desc');

        if (! empty($filters['shop_ids'])) {
            $query->whereIn('shop_id', $filters['shop_ids']);
        }

        if (! empty($filters['shop_id'])) {
            $query->where('shop_id', $filters['shop_id']);
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('sale_date', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('sale_date', '<=', $filters['end_date']);
        }

        return $query->get()->groupBy(function ($sale) {
            return $sale->sale_date->format('Y-m-d');
        });
    }

    /**
     * Detailed sales rows for a date range (used by daily/monthly modals).
     */
    public function getSalesDetailsByDateRange($filters = [], $startDate = null, $endDate = null)
    {
        $query = Sale::with(['shop:id,name', 'product:id,name'])
            ->whereDate('sale_date', '>=', $startDate)
            ->whereDate('sale_date', '<=', $endDate)
            ->orderBy('sale_date', 'desc')
            ->orderBy('id', 'desc');

        if (! empty($filters['shop_ids'])) {
            $query->whereIn('shop_id', $filters['shop_ids']);
        }

        if (! empty($filters['shop_id'])) {
            $query->where('shop_id', $filters['shop_id']);
        }

        return $query->get();
    }

    /**
     * Daily Profit/Loss report for a given month.
     */
    public function getDailyProfitLoss($filters = [])
    {
        $month = $filters['month'] ?? now()->format('Y-m');
        $startDate = $month.'-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        // --- aggregate query builder (reused) ---
        $buildQuery = function ($shopFilter = null) use ($startDate, $endDate) {
            $query = DB::table('sales')
                ->join('products', 'sales.product_id', '=', 'products.id')
                ->whereDate('sales.sale_date', '>=', $startDate)
                ->whereDate('sales.sale_date', '<=', $endDate);

            if ($shopFilter) {
                $query->where('sales.shop_id', $shopFilter);
            }

            return $query->select(
                DB::raw('DATE(sales.sale_date) as date'),
                DB::raw('COUNT(sales.id) as total_sales_count'),
                DB::raw('SUM(sales.total_amount) as total_revenue'),
                DB::raw('SUM(sales.quantity * products.purchase_price) as total_cost'),
                DB::raw('SUM(sales.profit) as total_profit')
            )
                ->groupBy(DB::raw('DATE(sales.sale_date)'))
                ->orderBy('date', 'asc')
                ->get();
        };

        $shopId = $filters['shop_id'] ?? null;
        $shopIdsFilter = $filters['shop_ids'] ?? [];

        // Main (all or single shop) daily rows
        $dailyRows = $buildQuery($shopId, $shopIdsFilter);

        // Per-shop breakdown when "All Shops"
        $shopBreakdown = collect();
        if (empty($shopId)) {
            $shopQuery = Shop::query();
            if (! empty($shopIdsFilter)) {
                $shopQuery->whereIn('id', $shopIdsFilter);
            }
            $shops = $shopQuery->get();
            foreach ($shops as $shop) {
                $rows = $buildQuery($shop->id);
                if ($rows->isNotEmpty()) {
                    $shopBreakdown->put($shop->id, (object) [
                        'shop' => $shop,
                        'rows' => $rows,
                    ]);
                }
            }
        }

        // Monthly totals
        $totals = (object) [
            'total_sales_count' => $dailyRows->sum('total_sales_count'),
            'total_revenue' => $dailyRows->sum('total_revenue'),
            'total_cost' => $dailyRows->sum('total_cost'),
            'total_profit' => $dailyRows->sum('total_profit'),
        ];

        return [
            'rows' => $dailyRows,
            'totals' => $totals,
            'shopBreakdown' => $shopBreakdown,
            'month' => $month,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    /**
     * Monthly Profit/Loss report for a given year.
     */
    public function getMonthlyProfitLoss($filters = [])
    {
        $year = $filters['year'] ?? now()->format('Y');
        $startDate = $year.'-01-01';
        $endDate = $year.'-12-31';

        $driver = DB::getDriverName();

        // Use driver-appropriate SQL to extract month number from sale_date
        if ($driver === 'sqlite') {
            // SQLite: strftime('%m') returns zero-padded month; cast to integer
            $monthSelect = DB::raw("CAST(strftime('%m', sales.sale_date) AS integer) as month_number");
            $monthGroup = DB::raw("CAST(strftime('%m', sales.sale_date) AS integer)");
        } else {
            // MySQL/Postgres: use MONTH()
            $monthSelect = DB::raw('MONTH(sales.sale_date) as month_number');
            $monthGroup = DB::raw('MONTH(sales.sale_date)');
        }

        $buildQuery = function ($shopFilter = null) use ($startDate, $endDate, $monthSelect, $monthGroup) {
            $query = DB::table('sales')
                ->join('products', 'sales.product_id', '=', 'products.id')
                ->whereDate('sales.sale_date', '>=', $startDate)
                ->whereDate('sales.sale_date', '<=', $endDate);

            if ($shopFilter) {
                $query->where('sales.shop_id', $shopFilter);
            }

            return $query->select(
                $monthSelect,
                DB::raw('COUNT(sales.id) as total_sales_count'),
                DB::raw('SUM(sales.total_amount) as total_revenue'),
                DB::raw('SUM(sales.quantity * products.purchase_price) as total_cost'),
                DB::raw('SUM(sales.profit) as total_profit')
            )
                ->groupBy($monthGroup)
                ->orderBy('month_number', 'asc')
                ->get();
        };

        $shopId = $filters['shop_id'] ?? null;
        $shopIdsFilter = $filters['shop_ids'] ?? [];

        $monthlyRows = $buildQuery($shopId, $shopIdsFilter);

        // Add profit margin to each row
        $monthlyRows = $monthlyRows->map(function ($row) {
            $row->profit_margin = $row->total_revenue > 0
                ? round(($row->total_profit / $row->total_revenue) * 100, 1)
                : 0;

            return $row;
        });

        // Per-shop breakdown when "All Shops"
        $shopBreakdown = collect();
        if (empty($shopId)) {
            $shopQuery = Shop::query();
            if (! empty($shopIdsFilter)) {
                $shopQuery->whereIn('id', $shopIdsFilter);
            }
            $shops = $shopQuery->get();
            foreach ($shops as $shop) {
                $rows = $buildQuery($shop->id);
                if ($rows->isNotEmpty()) {
                    $rows = $rows->map(function ($row) {
                        $row->profit_margin = $row->total_revenue > 0
                            ? round(($row->total_profit / $row->total_revenue) * 100, 1)
                            : 0;

                        return $row;
                    });
                    $shopBreakdown->put($shop->id, (object) [
                        'shop' => $shop,
                        'rows' => $rows,
                    ]);
                }
            }
        }

        // Yearly totals
        $totalRevenue = $monthlyRows->sum('total_revenue');
        $totalProfit = $monthlyRows->sum('total_profit');
        $totals = (object) [
            'total_sales_count' => $monthlyRows->sum('total_sales_count'),
            'total_revenue' => $totalRevenue,
            'total_cost' => $monthlyRows->sum('total_cost'),
            'total_profit' => $totalProfit,
            'profit_margin' => $totalRevenue > 0 ? round(($totalProfit / $totalRevenue) * 100, 1) : 0,
        ];

        return [
            'rows' => $monthlyRows,
            'totals' => $totals,
            'shopBreakdown' => $shopBreakdown,
            'year' => $year,
        ];
    }
}
