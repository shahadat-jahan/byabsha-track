<?php

namespace Modules\Sale\Models;

use App\Models\TenantModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shop\Models\Shop;

class SaleWarranty extends TenantModel
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'shop_id',
        'warranty_code',
        'source_type',
        'duration_snapshot_value',
        'duration_snapshot_unit',
        'coverage_quantity',
        'start_date',
        'end_date',
        'status',
        'terms',
        'claim_note',
        'claimed_at',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'claimed_at' => 'datetime',
        'coverage_quantity' => 'integer',
    ];

    public function getIsWithinServicePeriodAttribute(): bool
    {
        return $this->status === 'active' && $this->end_date && ! $this->end_date->isPast();
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class)->withTrashed();
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class)->withTrashed();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function scopeWithEffectiveStatus($query)
    {
        return $query->when(true, function ($q) {
            $q->selectRaw("sale_warranties.*, CASE WHEN status = 'active' AND end_date < CURDATE() THEN 'expired' ELSE status END as effective_status");
        });
    }
}
