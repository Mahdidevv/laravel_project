<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'product_attributes';
    protected $guarded = [];

    public function attribute():BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
