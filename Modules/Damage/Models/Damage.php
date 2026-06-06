<?php

namespace Modules\Damage\Models;

use App\Models\TenantModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shop\Models\Shop;

class Damage extends TenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shop_id',
        'reference_no',
        'damage_date',
        'total_quantity',
        'total_loss',
        'note',
        'created_by',
    ];

    protected $casts = [
        'shop_id' => 'integer',
        'created_by' => 'integer',
        'damage_date' => 'date',
        'total_quantity' => 'integer',
        'total_loss' => 'decimal:2',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class)->withTrashed();
    }

    public function items()
    {
        return $this->hasMany(DamageItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }
}
