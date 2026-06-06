<?php

namespace Modules\Subscription\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Branch\Models\Branch;
use Modules\Shop\Models\Shop;

class Subscription extends Model
{
    protected $fillable = [
        'user_id', 'shop_id', 'branch_id', 'subscription_plan_id',
        'status', 'starts_at', 'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && ($this->ends_at === null || $this->ends_at->isFuture());
    }

    public function inGracePeriod(): bool
    {
        return $this->status === 'active'
            && $this->ends_at
            && $this->ends_at->isPast()
            && $this->ends_at->gt(now()->subDays(3));
    }

    public function isExpiringSoon(): bool
    {
        return $this->status === 'active'
            && $this->ends_at
            && $this->ends_at->isFuture()
            && $this->ends_at->lt(now()->addDays(3));
    }
}
