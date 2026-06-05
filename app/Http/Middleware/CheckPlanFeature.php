<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\PlanService;
use Illuminate\Support\Facades\Log;

class CheckPlanFeature
{
    protected PlanService $plans;

    public function __construct(PlanService $plans)
    {
        $this->plans = $plans;
    }

    /**
     * Handle an incoming request.
     * Usage in routes: ->middleware('plan.check:shops') or 'plan.check:branches'
     */
    public function handle(Request $request, Closure $next, $feature = null)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        if (!$feature) {
            return $next($request);
        }

        try {
            // Feature-level enablement
            if (!$this->plans->isFeatureEnabled($user, $feature)) {
                return redirect()->back()->with('error', 'This feature is not available on your plan. Please upgrade to access.');
            }

            // Creation limits (for POST or showing create page)
            if (in_array($feature, ['shops','brands','categories','products','sales'])) {
                // For GET create page and POST store, enforce limit
                if ($request->isMethod('post') || $request->is('*/create')) {
                    if (!$this->plans->canCreate($user, $feature)) {
                        return redirect()->back()->with('error', 'Your current plan limits reached for ' . ucfirst($feature) . '. Please upgrade.');
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('Plan check failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to verify your plan at this time.');
        }

        return $next($request);
    }
}
