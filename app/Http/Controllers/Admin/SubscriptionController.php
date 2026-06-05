<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Subscription\Models\SubscriptionPlan;

class SubscriptionController extends Controller
{
    /**
     * Show the subscription plans admin page.
     */
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('sort_order')->get();

        return view('admin.subscriptions', compact('plans'));
    }
}
