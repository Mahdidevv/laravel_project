<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $guarded = [];

    public function getStatusAttribute($status)
    {
        switch ($status)
        {
            case '0' :
            {
               $status =  'در انتظار پرداخت';
                break;
            }

            case '1' :
            {
                $status =  ' پرداخت شده';
                break;
            }
        }
        return $status;
    }

    public function getPaymentTypeAttribute($paymentType)
    {
        switch ($paymentType)
        {
            case 'online' :
            {
                $paymentType =  'اینترنتی';
                break;
            }

            case 'cardToCard' :
            {
                $paymentType =  ' کارت به کارت';
                break;
            }
        }
        return $paymentType;
    }

    public function getPaymentStatusAttribute($paymentStatus)
    {
        switch ($paymentStatus)
        {
            case '0' :
            {
                $paymentStatus =  'ناموفق';
                break;
            }

            case '1' :
            {
                $paymentStatus =  ' موفق';
                break;
            }
        }
        return $paymentStatus;
    }
    public function orderItems():HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon():BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function address():BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
