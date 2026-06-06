<?php

namespace Modules\Capital\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PlanService;
use Modules\Capital\Services\CapitalService;

class CapitalController extends Controller
{
    protected $capitalService;

    public function __construct(CapitalService $capitalService)
    {
        $this->capitalService = $capitalService;
    }

    public function index()
    {
        $user = auth()->user();
        $planService = app(PlanService::class);
        if (! $planService->isFeatureEnabled($user, 'capitals')) {
            return redirect()->route('dashboard.index')->with('error', 'Capitals are not available on your current plan. Please upgrade to access this feature.');
        }
        $shopIds = $user->accessibleShopIds();
        $capitals = $this->capitalService->getAllShopCapitals($shopIds);

        return view('capital::index', compact('capitals'));
    }

    public function updateAll()
    {
        $user = auth()->user();
        $shopIds = $user->accessibleShopIds();
        $this->capitalService->updateAllShopsCapital($shopIds);

        return redirect()->route('capital.index')
            ->with('success', 'All shop capitals updated successfully!');
    }

    public function updateShop($shopId)
    {
        $user = auth()->user();
        abort_unless($user->ownsShop((int) $shopId), 403, 'You do not have access to this shop.');

        $totalCapital = $this->capitalService->updateShopCapital($shopId);

        return redirect()->route('capital.index')
            ->with('success', 'Shop capital updated successfully! New capital: '.currency_symbol().number_format($totalCapital, 2));
    }
}
