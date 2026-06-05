<?php

namespace Modules\Damage\Models;

use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductBatch;

class DamageItem extends TenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'damage_id',
        'product_id',
        'product_batch_id',
        'quantity',
        'purchase_price_per_unit',
        'total_loss',
        'reason',
        'reason_note',
    ];

    protected $casts = [
        'damage_id' => 'integer',
        'product_id' => 'integer',
        'product_batch_id' => 'integer',
        'quantity' => 'integer',
        'purchase_price_per_unit' => 'decimal:2',
        'total_loss' => 'decimal:2',
    ];

    public function damage()
    {
        return $this->belongsTo(Damage::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function productBatch()
    {
        return $this->belongsTo(ProductBatch::class, 'product_batch_id')->withTrashed();
    }
}
