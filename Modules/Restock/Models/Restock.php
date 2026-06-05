<?php

namespace Modules\Restock\Models;

use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductBatch;
use Modules\Shop\Models\Shop;

class Restock extends TenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'product_batch_id',
        'shop_id',
        'quantity',
        'remaining_quantity',
        'purchase_price_per_unit',
        'total_cost',
        'restock_date',
        'note',
    ];

    protected $casts = [
        'restock_date' => 'date',
        'quantity' => 'integer',
        'remaining_quantity' => 'integer',
        'product_batch_id' => 'integer',
        'purchase_price_per_unit' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function productBatch()
    {
        return $this->belongsTo(ProductBatch::class, 'product_batch_id')->withTrashed();
    }

    public function saleBatchItems()
    {
        return $this->hasMany(\Modules\Sale\Models\SaleBatchItem::class);
    }

    /**
     * Units consumed from this batch (quantity - remaining_quantity).
     */
    public function getConsumedQuantityAttribute(): int
    {
        return $this->quantity - $this->remaining_quantity;
    }
}
