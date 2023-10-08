<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addresses = Address::where('user_id',auth()->id())->with(['province','city'])->get();
        $provinces = Province::all();
        $cities = City::all();
        return view('home.users_profile.addresses',compact('provinces','addresses','cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validateWithBag('addressStore',[
            'title'=>'required',
            'cellphone'=>'required',
            'province_id'=>'required|exists:provinces,id',
            'city_id'=>'required|exists:cities,id',
            'address'=>'required',
            'postal_code'=>'required|integer',
        ]);
        try
        {
            DB::beginTransaction();
            Address::create([
                'title'=>$request->title,
                'user_id'=>auth()->id(),
                'cellphone'=>$request->cellphone,
                'province_id'=>$request->province_id,
                'city_id'=>$request->city_id,
                'address'=>$request->address,
                'postal_code'=>$request->postal_code,
            ]);
            DB::commit();
        }
        catch (\Exception $ex)
        {
            DB::rollBack();
            alert('شکست',$ex->getMessage());
            return redirect()->back();
        }
        alert()->success('موفق','آدرس با موفقیت ثبت شد');
        return redirect()->route('home.addresses.user_profile.index');
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
    public function update(Request $request, Address $address)
    {
        $validator = Validator::make($request->all(),[
            'title'=>'required',
            'cellphone'=>'required',
            'province_id'=>'required|exists:provinces,id',
            'city_id'=>'required|exists:cities,id',
            'address'=>'required',
            'postal_code'=>'required|integer',
        ]);

        if ($validator->fails())
        {
           $validator->errors()->all('address_id',$address->id);
           return redirect()->back()->withErrors($validator,'addressUpdate')->withInput();
        }
        $address->update([
            'title'=>$request->title,
            'cellphone'=>$request->cellphone,
            'province_id'=>$request->province_id,
            'city_id'=>$request->city_id,
            'address'=>$request->address,
            'postal_code'=>$request->postal_code,
        ]);
        alert()->success('موفق','آدرس مورد نظر آپدیت شد');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getProvinceCitiesList(Request $request)
    {
        $cities =City::where('province_id',$request->province_id)->get();

        return $cities;
    }
}
