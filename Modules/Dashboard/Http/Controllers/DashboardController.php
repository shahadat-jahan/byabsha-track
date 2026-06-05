<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Dashboard\Services\DashboardService;
use Modules\Capital\Models\Capital;
use Modules\Sale\Models\Sale;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the dashboard with statistics for each shop.
     */
    public function index()
    {
        $user = auth()->user();
        $shopMetrics = $this->dashboardService->getShopMetrics($user);
        $overallMetrics = $this->dashboardService->getOverallMetrics($user);
        $isBasicPlan = $user->isBasicPlan();
        $currentPlan = $user->getCurrentPlanKey();

        $userShops = $user->isSuperAdmin() 
            ? \Modules\Shop\Models\Shop::orderBy('name')->get()
            : $user->shops()->orderBy('name')->get();

        $activeShopId = app(\App\Services\ShopContext::class)->getActiveShopId();

        return view('dashboard::index', compact('shopMetrics', 'overallMetrics', 'isBasicPlan', 'currentPlan', 'userShops', 'activeShopId'));
    }

    /**
     * Switch active shop context in session.
     */
    public function selectShop(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|integer|exists:shops,id'
        ]);

        $user = auth()->user();
        $shopId = $request->integer('shop_id');

        if ($user->isSuperAdmin() || $user->ownsShop($shopId)) {
            session(['current_shop_id' => $shopId]);
            return back()->with('success', 'Active shop context switched.');
        }

        abort(403);
    }

    /**
     * Return shop details for modal AJAX request.
     */
    public function shopDetails($shopId)
    {
        $user = auth()->user();
        abort_unless($user->ownsShop((int) $shopId), 403, 'You do not have access to this shop.');

        $shop = \Modules\Shop\Models\Shop::with(['products', 'sales'])->findOrFail($shopId);
        $capital = Capital::where('shop_id', $shopId)->first();
        $todaySales = Sale::where('shop_id', $shopId)
            ->whereDate('sale_date', today())
            ->sum('total_amount');
        $todayProfit = Sale::where('shop_id', $shopId)
            ->whereDate('sale_date', today())
            ->sum('profit');
        $monthlyProfit = Sale::where('shop_id', $shopId)
            ->whereYear('sale_date', now()->year)
            ->whereMonth('sale_date', now()->month)
            ->sum('profit');
        return view('dashboard::partials.shop-details', [
            'shop' => $shop,
            'capital' => $capital,
            'todaySales' => $todaySales,
            'todayProfit' => $todayProfit,
            'monthlyProfit' => $monthlyProfit,
        ]);
    }
}
