<?php

namespace Modules\Subscription\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Branch\Models\Branch;
use Modules\Shop\Models\Shop;
use Modules\Subscription\Models\PaymentRequest;
use Modules\Subscription\Models\Subscription;
use Modules\Subscription\Models\SubscriptionPlan;

class SubscriptionController extends Controller
{
    public function plans()
    {
        $plans        = SubscriptionPlan::where('is_active', true)
            ->where(function ($q) {
                $q->whereRaw("JSON_EXTRACT(COALESCE(meta, '{}'), '$.show_in_owner_panel') = true")
                  ->orWhere('slug', 'free');
            })
            ->orderBy('sort_order')
            ->get();
        $user         = request()->user();
        $subscription = $user->activeSubscription();
        $currentPlan  = $subscription?->plan ?? SubscriptionPlan::freePlan();

        $pendingRequest = PaymentRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        $shops = collect();
        if ($user->isOwner()) {
            $shops = Shop::where('user_id', $user->id)
                ->with(['branches' => fn($q) => $q->where('is_active', true)->orderBy('name')])
                ->orderBy('name')
                ->get();
        } elseif ($user->isManager() && $user->shop_id) {
            $shops = Shop::where('id', $user->shop_id)
                ->with(['branches' => fn($q) => $q->where('is_active', true)->orderBy('name')])
                ->get();
        }

        return view('subscription::plans', compact('plans', 'currentPlan', 'pendingRequest', 'shops'));
    }

    public function mySubscription()
    {
        $user         = request()->user();
        $subscription = $user->activeSubscription();
        $currentPlan  = $subscription?->plan ?? SubscriptionPlan::freePlan();
        $query = PaymentRequest::query()->with(['plan', 'shop', 'branch']);
        if ($user->isOwner()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isManager() && $user->shop_id) {
            $query->where('shop_id', $user->shop_id);
        } else {
            $query->where('user_id', $user->id);
        }
        $history = $query->latest()->paginate(10);

        return view('subscription::my-subscription', compact('subscription', 'currentPlan', 'history'));
    }

    public function submitPayment(Request $request)
    {
        $user = request()->user();

        $rules = [
            'plan_id'             => 'required|exists:subscription_plans,id',
            'sender_bkash_number' => ['required', 'string', 'regex:/^01[3-9]\d{8}$/'],
            'transaction_id'      => 'required|string|max:100',
            'receipt_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'duration_months'     => 'required|integer|min:1|max:12',
        ];

        if ($user->isOwner()) {
            $rules['shop_id']   = 'required|exists:shops,id';
            $rules['branch_id'] = 'nullable|exists:branches,id';
        }

        $validated = $request->validate($rules);

        if ($user->isOwner()) {
            $shopId   = $validated['shop_id'];
            $branchId = $validated['branch_id'] ?? null;
            abort_unless(
                Shop::where('id', $shopId)->where('user_id', $user->id)->exists(),
                403
            );
            if ($branchId) {
                abort_unless(
                    Branch::where('id', $branchId)->where('shop_id', $shopId)->exists(),
                    403
                );
            }
        } else {
            $shopId   = $user->shop_id;
            $branchId = $user->branch_id;
        }

        $alreadyPending = PaymentRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) {
            if ($request->expectsJson()) {
                return response()->json(['message' => __('subscription::subscription.already_pending')], 422);
            }
            return back()->with('error', __('subscription::subscription.already_pending'));
        }

        $plan = SubscriptionPlan::findOrFail($validated['plan_id']);

        if ($plan->isFree()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => __('subscription::subscription.cannot_pay_free')], 422);
            }
            return back()->with('error', __('subscription::subscription.cannot_pay_free'));
        }

        $imagePath = null;
        if ($request->hasFile('receipt_image')) {
            $imagePath = $request->file('receipt_image')->store('payment-receipts', 'public');
        }

        PaymentRequest::create([
            'user_id'              => $user->id,
            'shop_id'              => $shopId,
            'branch_id'            => $branchId,
            'subscription_plan_id' => $plan->id,
            'amount'               => $plan->price * $validated['duration_months'],
            'sender_bkash_number'  => $validated['sender_bkash_number'],
            'transaction_id'       => $validated['transaction_id'],
            'receipt_image'        => $imagePath,
            'duration_months'      => $validated['duration_months'],
            'status'               => 'pending',
        ]);

        User::where('role', 'superadmin')->each(function (User $admin) use ($user, $plan, $validated): void {
            Notification::create([
                'user_id' => $admin->id,
                'type'    => 'payment_request',
                'title'   => 'New Payment Request',
                'message' => "{$user->name} submitted a payment for the {$plan->name} plan ({$validated['duration_months']} month(s)). Please review and verify.",
                'data'    => ['user_id' => $user->id, 'plan_slug' => $plan->slug],
            ]);
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('subscription::subscription.payment_submitted'),
                'redirect' => route('subscription.my')
            ]);
        }

        return redirect()->route('subscription.my')
            ->with('success', __('subscription::subscription.payment_submitted'));
    }
}
