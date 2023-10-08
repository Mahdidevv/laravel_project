<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;
    protected $table = 'addresses';
    protected $guarded = [];

    public function province():BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function city():BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}

