<?php

namespace Modules\Product\Models;

use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDynamicValue extends TenantModel
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_dynamic_field_id',
        'value',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function dynamicField(): BelongsTo
    {
        return $this->belongsTo(ProductDynamicField::class, 'product_dynamic_field_id');
    }
}
