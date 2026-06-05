<?php

namespace Modules\Shop\Models;

use App\Models\User;
use App\Models\TenantModel;
use Modules\Branch\Models\Branch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Models\Product;
use Modules\Sale\Models\Sale;

class Shop extends TenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'address',
        'user_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        if ($user->isManager()) {
            return $query->where('id', $user->shop_id);
        }

        return $query->where('user_id', $user->id);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function batches()
    {
        return $this->hasMany(\Modules\Product\Models\ProductBatch::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(\Modules\Subscription\Models\Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(\Modules\Subscription\Models\Subscription::class)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now()->subDays(3));
            })
            ->with('plan')
            ->latestOfMany();
    }

    public function hasFeature(string $featureKey): bool
    {
        // Core features allowed for any shop by default
        if (in_array($featureKey, ['dashboard', 'shop', 'subscription'], true)) {
            return true;
        }

        $subscription = $this->activeSubscription;
        $plan = $subscription?->plan ?? \Modules\Subscription\Models\SubscriptionPlan::freePlan();

        return $plan->hasModule($featureKey);
    }
}
