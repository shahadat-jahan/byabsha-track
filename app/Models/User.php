<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Route;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public const MODULE_ACCESS_KEYS = [
        'dashboard',
        'shop',
        'branch',
        'brand',
        'category',
        'product',
        'product_attributes',
        'stock',
        'sale',
        'capital',
        'restock',
        'damage',
        'report',
        'subscription',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'module_access',
        'is_approved',
        'shop_id',
        'branch_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'module_access' => 'array',
            'is_approved' => 'boolean',
            'shop_id' => 'integer',
            'branch_id' => 'integer',
        ];
    }

    /**
     * Check if user is superadmin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check if user is owner
     */
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    /**
     * Check if user is manager
     */
    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    /**
     * Check if manager is pending admin approval (is_approved === false, not null).
     */
    public function isPendingApproval(): bool
    {
        return $this->isManager() && $this->is_approved === false;
    }

    public static function availableModuleAccessKeys(): array
    {
        return self::MODULE_ACCESS_KEYS;
    }

    public function getModuleAccessList(): array
    {
        if ($this->isSuperAdmin()) {
            return self::MODULE_ACCESS_KEYS;
        }

        $configured = is_array($this->module_access) ? $this->module_access : [];

        if (count($configured) === 0) {
            // Backward compatibility for old users before module access was configured.
            return self::MODULE_ACCESS_KEYS;
        }

        return array_values(array_intersect(self::MODULE_ACCESS_KEYS, $configured));
    }

    public function hasModuleAccess(string $moduleKey): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // 1. Resolve current active shop context
        $shopId = app(\App\Services\ShopContext::class)->getActiveShopId();

        if (!$shopId) {
            // Permit access to core registration/setup modules if no shop exists yet
            return in_array($moduleKey, ['dashboard', 'shop', 'subscription'], true);
        }

        // 2. Fetch the shop to query its subscription features
        $shop = \Modules\Shop\Models\Shop::find($shopId);
        if (!$shop) {
            return false;
        }

        // 3. Verify module is enabled in active subscription for the shop
        if (!$shop->hasFeature($moduleKey)) {
            return false;
        }

        // 4. For managers, check granular overrides from the user's setup
        if ($this->isManager()) {
            return in_array($moduleKey, $this->getModuleAccessList(), true);
        }

        return true;
    }

    public function homeRouteName(): string
    {
        $moduleRouteMap = [
            'dashboard' => 'dashboard.index',
            'shop' => 'shop.index',
            'branch' => 'branch.index',
            'brand' => 'brand.index',
            'category' => 'category.index',
            'product' => 'product.index',
            'stock' => 'stock.index',
            'sale' => 'sale.index',
            'capital' => 'capital.index',
            'restock' => 'restock.index',
            'damage' => 'damage.index',
            'report' => 'report.index',
        ];

        foreach ($this->getModuleAccessList() as $moduleKey) {
            $routeName = $moduleRouteMap[$moduleKey] ?? null;

            if ($routeName && Route::has($routeName)) {
                return $routeName;
            }
        }

        return 'user.profile.edit';
    }

    /**
     * Get the user's notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get unread notifications count
     */
    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->unread()->count();
    }

    /**
     * Get all shops owned by this user (owner relationship).
     */
    public function shops()
    {
        return $this->hasMany(\Modules\Shop\Models\Shop::class, 'user_id');
    }

    /**
     * Get the shop assigned to this manager.
     */
    public function assignedShop()
    {
        return $this->belongsTo(\Modules\Shop\Models\Shop::class, 'shop_id');
    }

    /**
     * Get the branch assigned to this manager.
     */
    public function assignedBranch()
    {
        return $this->belongsTo(\Modules\Branch\Models\Branch::class, 'branch_id');
    }

    /**
     * Check if user owns a specific shop (superadmin always passes).
     */
    public function ownsShop(int $shopId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->isManager()) {
            return (int) $this->shop_id === $shopId;
        }

        return $this->shops()->whereKey($shopId)->exists();
    }

    /**
     * Return an array of shop IDs this user is allowed to access.
     * Superadmin gets all shop IDs via a DB query; owners get only their own;
     * managers get only their assigned shop.
     */
    public function accessibleShopIds(): array
    {
        if ($this->isSuperAdmin()) {
            return \Modules\Shop\Models\Shop::pluck('id')->all();
        }

        if ($this->isManager()) {
            return $this->shop_id ? [(int) $this->shop_id] : [];
        }

        return $this->shops()->pluck('id')->all();
    }

    public function subscriptions()
    {
        return $this->hasMany(\Modules\Subscription\Models\Subscription::class);
    }

    public function activeSubscription(): ?\Modules\Subscription\Models\Subscription
    {
        $shopId = app(\App\Services\ShopContext::class)->getActiveShopId();
        if ($shopId) {
            return \Modules\Subscription\Models\Subscription::where('shop_id', $shopId)
                ->where('status', 'active')
                ->where(function ($q) {
                    $q->whereNull('ends_at')->orWhere('ends_at', '>', now()->subDays(3));
                })
                ->with('plan')
                ->latest()
                ->first();
        }

        return $this->subscriptions()
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now()->subDays(3));
            })
            ->with('plan')
            ->latest()
            ->first();
    }

    public function currentPlan(): \Modules\Subscription\Models\SubscriptionPlan
    {
        return $this->activeSubscription()?->plan
            ?? \Modules\Subscription\Models\SubscriptionPlan::freePlan()
            ?? new \Modules\Subscription\Models\SubscriptionPlan([
                'name'           => 'Free Trial',
                'slug'           => 'free',
                'price'          => 0,
                'max_shops'      => null,
                'max_branches'   => null,
                'max_brands'     => null,
                'max_categories' => null,
                'max_sales'      => null,
                'has_capital'    => false,
                'has_restock'    => false,
                'has_reports'    => false,
                'is_trial'       => true,
                'trial_days'     => 30,
            ]);
    }

    /** True while the user's free trial subscription is still active. */
    public function onTrial(): bool
    {
        $shopId = app(\App\Services\ShopContext::class)->getActiveShopId();
        if ($shopId) {
            return \Modules\Subscription\Models\Subscription::where('shop_id', $shopId)
                ->whereHas('plan', fn($q) => $q->where('is_trial', true))
                ->where('status', 'active')
                ->where('ends_at', '>', now())
                ->exists();
        }

        return $this->subscriptions()
            ->whereHas('plan', fn($q) => $q->where('is_trial', true))
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->exists();
    }

    /** The date/time the user's trial ends, or null if no trial subscription exists. */
    public function trialEndsAt(): ?\Illuminate\Support\Carbon
    {
        $shopId = app(\App\Services\ShopContext::class)->getActiveShopId();
        if ($shopId) {
            $trial = \Modules\Subscription\Models\Subscription::where('shop_id', $shopId)
                ->whereHas('plan', fn($q) => $q->where('is_trial', true))
                ->latest()
                ->first();
            return $trial?->ends_at;
        }

        $trial = $this->subscriptions()
            ->whereHas('plan', fn($q) => $q->where('is_trial', true))
            ->latest()
            ->first();

        return $trial?->ends_at;
    }

    /**
     * True when the free trial has expired and no active paid subscription exists.
     * Superadmins are never considered expired.
     */
    public function isTrialExpired(): bool
    {
        if ($this->isSuperAdmin()) {
            return false;
        }

        $shopId = app(\App\Services\ShopContext::class)->getActiveShopId();
        if ($shopId) {
            // Check if this shop has an active paid subscription
            $hasPaidSub = \Modules\Subscription\Models\Subscription::where('shop_id', $shopId)
                ->whereHas('plan', fn($q) => $q->where('is_trial', false)->where('price', '>', 0))
                ->where('status', 'active')
                ->where(fn($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', now()))
                ->exists();

            if ($hasPaidSub) {
                return false;
            }

            // Check if this shop has a trial subscription that is still active
            $trialSub = \Modules\Subscription\Models\Subscription::where('shop_id', $shopId)
                ->whereHas('plan', fn($q) => $q->where('is_trial', true))
                ->latest()
                ->first();

            if (!$trialSub) {
                // If there's no trial sub but we have a shop, treat as expired if shop is older than 30 days
                $shop = \Modules\Shop\Models\Shop::find($shopId);
                return $shop ? $shop->created_at->lt(now()->subDays(30)) : true;
            }

            return $trialSub->ends_at && $trialSub->ends_at->isPast();
        }

        // Fallback: check if the user has any active paid subscription
        $hasPaidSub = $this->subscriptions()
            ->whereHas('plan', fn($q) => $q->where('is_trial', false)->where('price', '>', 0))
            ->where('status', 'active')
            ->where(fn($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', now()))
            ->exists();

        if ($hasPaidSub) {
            return false;
        }

        $trialSub = $this->subscriptions()
            ->whereHas('plan', fn($q) => $q->where('is_trial', true))
            ->latest()
            ->first();

        if (!$trialSub) {
            return $this->created_at->lt(now()->subDays(30));
        }

        return $trialSub->ends_at && $trialSub->ends_at->isPast();
    }

    /**
     * Get the current subscription plan key.
     * Returns 'basic' (free) if no active subscription found.
     */
    public function getCurrentPlanKey(): string
    {
        if ($this->isSuperAdmin()) {
            return 'premium';
        }

        $activeSubscription = $this->activeSubscription();
        if ($activeSubscription && $activeSubscription->plan) {
            return $activeSubscription->plan->plan_key ?? 'basic';
        }

        return 'basic';
    }

    /**
     * Get the current subscription plan limits.
     */
    public function getPlanLimits(): array
    {
        return \App\Models\Subscription::getPlanLimits($this->getCurrentPlanKey());
    }

    /**
     * Check if user is on Basic (free) plan.
     */
    public function isBasicPlan(): bool
    {
        return $this->getCurrentPlanKey() === 'basic';
    }

    /**
     * Check if a feature is available for the user's current plan.
     */
    public function hasFeature(string $feature): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return \App\Models\Subscription::isFeatureAvailable($feature, $this->getCurrentPlanKey());
    }

    /**
     * Get limit value for a specific feature.
     */
    public function getFeatureLimit(string $feature): mixed
    {
        if ($this->isSuperAdmin()) {
            return 999999;
        }

        return \App\Models\Subscription::getFeatureLimits($feature, $this->getCurrentPlanKey());
    }

    /**
     * Check if user can use product attributes (dynamic fields).
     * Only superadmins and owners can manage product attributes.
     * Also checks subscription plan for non-admin users.
     */
    public function canUseProductAttributes(): bool
    {
        if ($this->isSuperAdmin() || $this->isOwner()) {
            return true;
        }

        return $this->hasFeature('product_attributes');
    }
}
