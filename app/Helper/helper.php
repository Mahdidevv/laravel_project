<?php

use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;

if (!function_exists('generateFileName')) {
    function generateFileName($name): string
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $day = Carbon::now()->day;
        $hour = Carbon::now()->hour;
        $minute = Carbon::now()->minute;
        $second = Carbon::now()->second;
        $microsecond = Carbon::now()->microsecond;
        return $year . '_' . $month . '_' . $day . '_' . $hour . '_' . $minute . '_' . $second . '_' . $microsecond . '_' . $name;
    }

}
if (!function_exists('jalaliToGregorian')) {
    function jalaliToGregorian($date)
    {
        if ($date == null) {
            return null;
        }
        $faToEnglishNumber = Verta::faToEnNumbers($date);
        $pattern = '/[-\s]/';
        $dateSplit = preg_split($pattern, $faToEnglishNumber);
        $gregorian = Verta::jalaliToGregorian((integer)$dateSplit[0], (integer)$dateSplit[1], (integer)$dateSplit[2]);
        return implode('-', $gregorian) . " " . $dateSplit[3];
    }
}
if (!function_exists('calculatorSalePriceAmount')) {
    function calculatorSalePriceAmount()
    {
        $salePriceAmount = 0;
        foreach (\Cart::getContent() as $item) {
            if ($item->attributes->isSale) {
                $salePriceAmount += ($item->quantity) * ($item->attributes->price - $item->attributes->sale_price);
            }
        }
        return $salePriceAmount;
    }
}
if (!function_exists('calculatorDeliveryAmount')) {
    function calculatorDeliveryAmount()
    {
        $deliveryAmount = 0;
        foreach (\Cart::getContent() as $item) {
            $deliveryAmount += $item->associatedModel->delivery_amount;
        }
        return $deliveryAmount;
    }
}
if (!function_exists('getTotalAmount')) {
    function getTotalAmount()
    {
        $totalAmount = 0;
        if (session()->has('coupon')) {
            $totalAmount = (\Cart::getTotal() + calculatorDeliveryAmount() - session()->get('coupon.amount'));
        } else {
            $totalAmount = (\Cart::getTotal() + calculatorDeliveryAmount());

        }

        return $totalAmount;
    }
}
