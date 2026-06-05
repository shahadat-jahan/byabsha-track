<?php

namespace Modules\Subscription\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name', 'slug', 'price', 'duration_days', 'status', 'billing_cycle', 'description',
        'max_shops', 'max_branches', 'max_brands', 'max_categories', 'max_sales',
        'has_capital', 'has_restock', 'has_reports', 'has_priority_support',
        'is_active', 'sort_order', 'is_trial', 'trial_days', 'features', 'limits', 'meta',
    ];

    protected $casts = [
        'duration_days'         => 'integer',
        'has_capital'           => 'boolean',
        'has_restock'           => 'boolean',
        'has_reports'           => 'boolean',
        'has_priority_support'  => 'boolean',
        'is_active'             => 'boolean',
        'is_trial'              => 'boolean',
        'features'              => 'array',
        'limits'                => 'array',
        'meta'                  => 'array',
    ];

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function isFree(): bool
    {
        return $this->slug === 'free' || $this->price == 0;
    }

    public function isTrial(): bool
    {
        return (bool) $this->is_trial;
    }

    public function isUnlimited(string $feature): bool
    {
        return $this->{"max_{$feature}"} === null;
    }

    public static function freePlan(): self
    {
        $freePlan = static::where('slug', 'free')->first();

        if ($freePlan) {
            return $freePlan;
        }

        return new static([
            'name' => 'Basic',
            'slug' => 'free',
            'price' => 0,
            'billing_cycle' => 'month',
            'description' => 'Starter free plan',
            'max_shops' => null,
            'max_branches' => null,
            'max_brands' => null,
            'max_categories' => null,
            'max_sales' => null,
            'has_capital' => false,
            'has_restock' => false,
            'has_reports' => false,
            'has_priority_support' => false,
            'is_active' => true,
            'sort_order' => 0,
            'is_trial' => false,
            'trial_days' => 0,
        ]);
    }

    public function formattedPrice(): string
    {
        if ($this->isFree()) {
            return 'Free';
        }
        return '' . number_format($this->price) . '/' . $this->billing_cycle;
    }

    /**
     * Get a feature flag from the JSON features column, falling back to boolean columns for compatibility.
     */
    public function getFeature(string $key, $default = false)
    {
        $map = [
            'branches'   => 'branch',
            'capitals'   => 'capital',
            'damages'    => 'damage',
            'stocks'     => 'stock',
            'reports'    => 'report',
            'daily_pl'   => 'report',
            'monthly_pl' => 'report',
        ];

        $normalizedKey = $map[$key] ?? $key;

        $features = (array) ($this->features ?? []);
        if (array_key_exists($normalizedKey, $features)) {
            return (bool) $features[$normalizedKey];
        }

        $legacyMap = [
            'capital'  => 'has_capital',
            'capitals' => 'has_capital',
            'restock'  => 'has_restock',
            'report'   => 'has_reports',
            'reports'  => 'has_reports',
        ];

        $legacyField = $legacyMap[$normalizedKey] ?? $legacyMap[$key] ?? null;
        if ($legacyField && (isset($this->{$legacyField}) || (is_object($this) && property_exists($this, $legacyField)))) {
            return (bool) $this->{$legacyField};
        }

        return (bool) $default;
    }

    /**
     * Check if the plan includes a specific module.
     */
    public function hasModule(string $moduleKey): bool
    {
        return $this->getFeature($moduleKey, false);
    }

    /**
     * Get a numeric limit value from the JSON limits column, falling back to legacy max_* columns.
     */
    public function getLimit(string $key)
    {
        $limits = (array) ($this->limits ?? []);
        if (array_key_exists($key, $limits)) {
            return $limits[$key];
        }

        $map = [
            'shops' => 'max_shops',
            'branches' => 'max_branches',
            'brands' => 'max_brands',
            'categories' => 'max_categories',
            'sales' => 'max_sales',
            'products' => null, // products might be calculated from other columns
        ];

        if (isset($map[$key]) && $map[$key] && property_exists($this, $map[$key])) {
            return $this->{$map[$key]};
        }

        return null;
    }

    public function getMeta(string $key, $default = null)
    {
        $meta = (array) ($this->meta ?? []);

        return array_key_exists($key, $meta) ? $meta[$key] : $default;
    }

    public function badgeLabel(): ?string
    {
        return $this->getMeta('badge_label');
    }

    public function buttonText(): string
    {
        return $this->getMeta('button_text', 'Submit Payment');
    }
}
