<?php

namespace App\Models;

use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    protected $guarded = '';

    public function scopeGetData($query,$month,$status)
    {
        $dateJalali = verta()->subMonths($month-1)->startMonth();
        $dateGregorian = Verta::jalaliToGregorian($dateJalali->year,$dateJalali->month,$dateJalali->day);
        $transaction = Transaction::where('created_at','>',Carbon::create($dateGregorian[0],$dateGregorian[1],$dateGregorian[2],0,0,0))->get();
        return $query->where('status',$status)->get();
    }
}
