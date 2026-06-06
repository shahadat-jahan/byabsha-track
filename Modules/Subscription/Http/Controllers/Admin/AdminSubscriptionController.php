<?php

namespace Modules\Subscription\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Modules\Branch\Models\Branch;
use Modules\Shop\Models\Shop;
use Modules\Subscription\Models\PaymentRequest;
use Modules\Subscription\Models\Subscription;

class AdminSubscriptionController extends Controller
{
    /**
     * Get a sanitized scalar value from request input
     */
    private function getScalarInput($request, $key, $default = '')
    {
        $value = $request->input($key, $default);
        if (is_array($value)) {
            return (string) (reset($value) ?: $default);
        }

        return (string) $value;
    }

    public function index(Request $request)
    {
        $status = $this->getScalarInput($request, 'status', 'pending');
        $shopId = $this->getScalarInput($request, 'shop_id', '');
        $branchId = $this->getScalarInput($request, 'branch_id', '');
        $search = trim($this->getScalarInput($request, 'search', ''));

        if (! in_array($status, ['pending', 'approved', 'rejected'])) {
            $status = 'pending';
        }

        $query = PaymentRequest::with(['user', 'plan', 'shop', 'branch'])->latest();

        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }
        if ($shopId) {
            $query->where('shop_id', $shopId);
        }
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }
        if ($search !== '') {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $requests = $query->paginate(20)->withQueryString();

        $counts = [
            'pending' => PaymentRequest::where('status', 'pending')->count(),
            'approved' => PaymentRequest::where('status', 'approved')->count(),
            'rejected' => PaymentRequest::where('status', 'rejected')->count(),
        ];

        $shops = Shop::with('branches')->orderBy('name')->get();
        $branches = $shopId
            ? Branch::where('shop_id', $shopId)->orderBy('name')->get()
            : Branch::orderBy('name')->get();

        $shopsJson = $shops->map(function ($shop) {
            return [
                'id' => $shop->id,
                'name' => $shop->name,
                'branches' => $shop->branches->map(fn ($b) => ['id' => $b->id, 'name' => $b->name])->toArray(),
            ];
        })->keyBy('id')->toArray();

        return view('subscription::admin.index', compact(
            'requests', 'counts', 'status', 'shops', 'branches',
            'shopId', 'branchId', 'search', 'shopsJson'
        ));
    }

    public function active(Request $request)
    {
        $shopId = $this->getScalarInput($request, 'shop_id', '');
        $branchId = $this->getScalarInput($request, 'branch_id', '');
        $search = trim($this->getScalarInput($request, 'search', ''));

        $query = Subscription::with(['user', 'plan', 'shop', 'branch'])
            ->where('status', 'active')
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', now()))
            ->latest('starts_at');

        if ($shopId) {
            $query->where('shop_id', $shopId);
        }
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }
        if ($search !== '') {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $subscriptions = $query->paginate(20)->withQueryString();

        $shops = Shop::with('branches')->orderBy('name')->get();
        $branches = $shopId
            ? Branch::where('shop_id', $shopId)->orderBy('name')->get()
            : Branch::orderBy('name')->get();

        $counts = [
            'pending' => PaymentRequest::where('status', 'pending')->count(),
            'approved' => PaymentRequest::where('status', 'approved')->count(),
            'rejected' => PaymentRequest::where('status', 'rejected')->count(),
        ];

        return view('subscription::admin.active', compact(
            'subscriptions', 'counts', 'shops', 'branches',
            'shopId', 'branchId', 'search'
        ));
    }

    public function show(PaymentRequest $paymentRequest)
    {
        $paymentRequest->load(['user', 'plan', 'reviewer', 'shop', 'branch']);

        return view('subscription::admin.show', compact('paymentRequest'));
    }

    public function approve(Request $request, PaymentRequest $paymentRequest)
    {
        if ($paymentRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been reviewed.');
        }

        $validated = $request->validate(['admin_note' => 'nullable|string|max:500']);

        $paymentRequest->loadMissing('plan');

        Subscription::where('shop_id', $paymentRequest->shop_id)
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        Subscription::create([
            'user_id' => $paymentRequest->user_id,
            'shop_id' => $paymentRequest->shop_id,
            'branch_id' => $paymentRequest->branch_id,
            'subscription_plan_id' => $paymentRequest->subscription_plan_id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays(($paymentRequest->plan->duration_days ?? 30) * $paymentRequest->duration_months),
        ]);

        $paymentRequest->update([
            'status' => 'approved',
            'admin_note' => $validated['admin_note'] ?? null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        Notification::create([
            'user_id' => $paymentRequest->user_id,
            'type' => 'payment_approved',
            'title' => 'Payment Approved — Subscription Activated',
            'message' => "Your payment for the {$paymentRequest->plan->name} plan has been approved. Your subscription is now active.",
            'data' => ['plan_slug' => $paymentRequest->plan->slug],
        ]);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Payment approved and subscription activated.');
    }

    public function reject(Request $request, PaymentRequest $paymentRequest)
    {
        if ($paymentRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been reviewed.');
        }

        $validated = $request->validate(['admin_note' => 'required|string|max:500']);

        $paymentRequest->loadMissing('plan');

        $paymentRequest->update([
            'status' => 'rejected',
            'admin_note' => $validated['admin_note'],
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        Notification::create([
            'user_id' => $paymentRequest->user_id,
            'type' => 'payment_rejected',
            'title' => 'Payment Request Rejected',
            'message' => "Your payment request for the {$paymentRequest->plan->name} plan was rejected. Reason: {$validated['admin_note']}",
            'data' => ['plan_slug' => $paymentRequest->plan->slug],
        ]);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Payment request rejected.');
    }
}
