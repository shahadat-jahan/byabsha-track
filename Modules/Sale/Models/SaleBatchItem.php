<?php

namespace Modules\Sale\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Restock\Models\Restock;

class SaleBatchItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'restock_id',
        'quantity',
        'purchase_price_per_unit',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'purchase_price_per_unit' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function restock()
    {
        return $this->belongsTo(Restock::class)->withTrashed();
    }
}
