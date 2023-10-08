<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.coupons.index',compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'code'=>'required',
            'type'=>'required',
            'amount'=>'required_if:type,=,amount',
            'percentage'=> 'required_if:type,=,percentage',
            'max_percentage_amount' =>'required_if:type,=,percentage',
            'expired_at' => 'required'

        ]);
        try
        {
            DB::beginTransaction();
            Coupon::create([
                'name'=>$request->name,
                'code'=>$request->code,
                'type'=>$request->type,
                'amount'=>$request->amount,
                'percentage'=>$request->percentage,
                'max_percentage_amount'=>$request->max_percentage_amount,
                'description'=>$request->description,
                'expired_at'=>jalaliToGregorian($request->expired_at),
            ]);
            DB::commit();
        }
        catch (Exception $ex)
        {
            DB::rollBack();
            alert()->error('شکست',$ex->getMessage());
            return redirect()->back();
        }
        alert()->success('موفق','کوپن مورد نظر ثبت شد');
        return redirect()->route('admin.coupons.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
