<?php

namespace Modules\Product\Models;

use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Damage\Models\DamageItem;
use Modules\Restock\Models\Restock;
use Modules\Sale\Models\Sale;
use Modules\Shop\Models\Shop;

class ProductBatch extends TenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'shop_id',
        'batch_code',
        'source_type',
        'source_id',
        'attribute_values',
        'purchase_price',
        'initial_quantity',
        'remaining_quantity',
        'batch_date',
        'note',
    ];

    protected $casts = [
        'shop_id' => 'integer',
        'product_id' => 'integer',
        'source_id' => 'integer',
        'attribute_values' => 'array',
        'purchase_price' => 'decimal:2',
        'initial_quantity' => 'integer',
        'remaining_quantity' => 'integer',
        'batch_date' => 'date',
    ];

    public function getAttributeSummaryAttribute(): string
    {
        $attributes = collect($this->attribute_values ?? [])
            ->filter(fn ($item) => is_array($item) && filled($item['value'] ?? null))
            ->map(function (array $item) {
                $label = (string) ($item['label'] ?? $item['field_key'] ?? 'Attribute');
                $value = (string) ($item['value'] ?? '');

                return trim($label) . ': ' . trim($value);
            })
            ->values();

        return $attributes->isNotEmpty() ? $attributes->implode(' | ') : '-';
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function restock()
    {
        return $this->hasOne(Restock::class, 'product_batch_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'product_batch_id');
    }

    public function damageItems()
    {
        return $this->hasMany(DamageItem::class, 'product_batch_id');
    }
}
