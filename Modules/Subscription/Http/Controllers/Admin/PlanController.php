<?php

namespace Modules\Subscription\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Subscription\Models\PaymentRequest;
use Modules\Subscription\Models\SubscriptionPlan;

class PlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('sort_order')->get();

        return view('subscription::admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('subscription::admin.plans.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,inactive',
            'features' => 'nullable|array',
            'meta' => 'nullable|string',
            'show_in_owner_panel' => 'nullable|boolean',
        ]);

        $plan = new SubscriptionPlan;
        $plan->name = $data['name'];

        $slug = Str::slug($data['name']);
        $count = SubscriptionPlan::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug.'-'.time();
        }
        $plan->slug = $slug;

        $plan->price = $data['price'];
        $plan->duration_days = $data['duration_days'];
        $plan->status = $data['status'];
        $plan->billing_cycle = $data['duration_days'].' days';
        $plan->description = $data['description'] ?? null;
        $plan->is_active = ($data['status'] === 'active');

        $features = [];
        if ($request->has('features') && is_array($request->input('features'))) {
            foreach ($request->input('features') as $key => $value) {
                $features[$key] = true;
            }
        }
        $plan->features = $features;

        $plan->limits = null;
        $meta = ($data['meta'] ?? null) ? json_decode($data['meta'], true) : [];
        $meta['show_in_owner_panel'] = $request->boolean('show_in_owner_panel');
        $plan->meta = $meta;

        $plan->save();

        return redirect()->route('admin.subscriptions.plans.index')->with('success', 'Plan created');
    }

    public function edit(SubscriptionPlan $plan)
    {
        return view('subscription::admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,inactive',
            'features' => 'nullable|array',
            'meta' => 'nullable|string',
            'show_in_owner_panel' => 'nullable|boolean',
        ]);

        $plan->name = $data['name'];

        $slug = Str::slug($data['name']);
        if ($plan->slug !== $slug) {
            $count = SubscriptionPlan::where('slug', $slug)->where('id', '!=', $plan->id)->count();
            if ($count > 0) {
                $slug = $slug.'-'.time();
            }
            $plan->slug = $slug;
        }

        $plan->price = $data['price'];
        $plan->duration_days = $data['duration_days'];
        $plan->status = $data['status'];
        $plan->billing_cycle = $data['duration_days'].' days';
        $plan->description = $data['description'] ?? null;
        $plan->is_active = ($data['status'] === 'active');

        $features = [];
        if ($request->has('features') && is_array($request->input('features'))) {
            foreach ($request->input('features') as $key => $value) {
                $features[$key] = true;
            }
        }
        $plan->features = $features;

        $plan->limits = null;
        $meta = ($data['meta'] ?? null) ? json_decode($data['meta'], true) : [];
        $meta['show_in_owner_panel'] = $request->boolean('show_in_owner_panel');
        $plan->meta = $meta;

        $plan->save();

        return redirect()->route('admin.subscriptions.plans.index')->with('success', 'Plan updated');
    }

    public function destroy(SubscriptionPlan $plan)
    {
        $hasSubscriptions = $plan->subscriptions()->exists();
        $hasPaymentRequests = PaymentRequest::where('subscription_plan_id', $plan->id)->exists();

        if ($hasSubscriptions || $hasPaymentRequests) {
            return redirect()->route('admin.subscriptions.plans.index')
                ->with('error', 'This plan cannot be deleted because it is linked to active subscriptions or payment history. You can deactivate it instead.');
        }

        $plan->delete();

        return redirect()->route('admin.subscriptions.plans.index')->with('success', 'Plan deleted successfully.');
    }
}
