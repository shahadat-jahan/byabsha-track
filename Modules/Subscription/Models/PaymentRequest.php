<?php

namespace Modules\Subscription\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Branch\Models\Branch;
use Modules\Shop\Models\Shop;

class PaymentRequest extends Model
{
    protected $fillable = [
        'user_id', 'shop_id', 'branch_id', 'subscription_plan_id', 'amount',
        'sender_bkash_number', 'transaction_id', 'receipt_image', 'status',
        'admin_note', 'reviewed_by', 'reviewed_at', 'duration_months',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
