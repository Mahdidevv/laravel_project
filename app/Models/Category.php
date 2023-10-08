<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $guarded = [];

    public function parent():BelongsTo
    {
        return $this->belongsTo(Category::class,'parent_id');
    }

    public function children():HasMany
    {
        return $this->hasMany(Category::class,'parent_id');
    }

    public function getIsActiveAttribute($is_active)
    {
        return $is_active ? 'فعال' : 'غیرفعال';
    }

    public function attributes():BelongsToMany
    {
        return $this->belongsToMany(Attribute::class,'attribute_category');
    }
    public function products():HasMany
    {
        return $this->hasMany(Product::class);
    }
}
