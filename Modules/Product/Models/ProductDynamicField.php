<?php

namespace Modules\Product\Models;

use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Category\Models\Category;

class ProductDynamicField extends TenantModel
{
    use HasFactory, SoftDeletes;

    public const INPUT_TYPES = ['text', 'number', 'textarea', 'select', 'date'];

    protected $fillable = [
        'category_id',
        'label',
        'field_key',
        'input_type',
        'placeholder',
        'help_text',
        'options',
        'is_required',
        'is_active',
        'sort_order',
        'created_by',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'created_by' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by')->withTrashed();
    }

    public function values(): HasMany
    {
        return $this->hasMany(ProductDynamicValue::class);
    }
}
