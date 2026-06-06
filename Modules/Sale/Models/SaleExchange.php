<?php

namespace Modules\Sale\Models;

use App\Models\TenantModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Models\ProductBatch;
use Modules\Shop\Models\Shop;

class SaleExchange extends TenantModel
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'sale_id',
        'original_batch_id',
        'replacement_batch_id',
        'quantity',
        'exchange_date',
        'exchange_type',
        'reason',
        'note',
        'cost_difference',
        'status',
        'created_by',
    ];

    protected $casts = [
        'exchange_date' => 'date',
        'quantity' => 'integer',
        'cost_difference' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class)->withTrashed();
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class)->withTrashed();
    }

    public function originalBatch()
    {
        return $this->belongsTo(ProductBatch::class, 'original_batch_id')->withTrashed();
    }

    public function replacementBatch()
    {
        return $this->belongsTo(ProductBatch::class, 'replacement_batch_id')->withTrashed();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }
}
