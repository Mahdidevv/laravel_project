<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Request;

class Coupon extends Model
{
    use HasFactory;
    protected $table='coupons';
    protected $guarded = [];


    public function getTypeAttribute($type)
    {
        return $type == 'amount' ? 'مبلغی' : 'درصدی';
    }

}
