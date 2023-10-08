<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $month = 6;
        $successTransactions = Transaction::getData($month,1);
        $successTransactionsChart = array_values($this->chart($successTransactions,$month));
        $labels = array_keys($this->chart($successTransactions,$month));
        $unSuccessTransactions = Transaction::getData($month,0);
        $unSuccessTransactionsChart =array_values($this->chart($unSuccessTransactions,$month));
        $transactionCount = [$successTransactions->count(),$unSuccessTransactions->count()];
        return view('admin.dashboard',compact('successTransactionsChart','unSuccessTransactionsChart','labels','transactionCount'));
    }

    public function chart($transaction,$month)
    {
        $monthName = $transaction->map(function ($item) {
            return verta($item->created_at)->format('%B %y');
        });
        $amount = $transaction->map(function ($item) {
            return $item->amount;
        });

        foreach ($monthName as $key => $value)
        {
            if (!isset($result[$value]))
            {
                $result[$value] = 0;
            }
            $result[$value] += $amount[$key];
        }

        if (count($result) != $month)
        {
            for ($i =0;$month>$i;$i++)
            {
                $monthName = \verta()->subMonths($i)->format('%B %y');
                $shamsiMonths[$monthName] = 0;
            }
            return array_reverse(array_merge($shamsiMonths,$result));
        }
        return $result;
    }
}
