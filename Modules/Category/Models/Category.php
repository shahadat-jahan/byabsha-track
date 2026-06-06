<?php

namespace Modules\Category\Models;

use App\Models\TenantModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Models\Product;

class Category extends TenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        return $query->where('user_id', $user->id);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
