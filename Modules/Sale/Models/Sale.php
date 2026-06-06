<?php

namespace Modules\Sale\Models;

use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductBatch;
use Modules\Shop\Models\Shop;

class Sale extends TenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shop_id',
        'product_id',
        'product_batch_id',
        'quantity',
        'sale_price',
        'purchase_price_per_unit',
        'discount',
        'total_amount',
        'profit',
        'sale_date',
        'customer_name',
        'customer_phone',
        'customer_address',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'quantity' => 'integer',
        'sale_price' => 'decimal:2',
        'purchase_price_per_unit' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'profit' => 'decimal:2',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class)->withTrashed();
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function batchItems()
    {
        return $this->hasMany(SaleBatchItem::class);
    }

    public function productBatch()
    {
        return $this->belongsTo(ProductBatch::class, 'product_batch_id')->withTrashed();
    }

    public function warranties()
    {
        return $this->hasMany(SaleWarranty::class);
    }

    public function exchanges()
    {
        return $this->hasMany(SaleExchange::class);
    }
}
