<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::latest()->paginate(20);
        return view('admin.permissions.index',compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'display_name'=>'required'
        ]);

        try
        {
            DB::beginTransaction();
            Permission::create([
                'name'=>$request->name,
                'display_name'=>$request->display_name
            ]);
            DB::commit();
        }
        catch (\Exception $ex)
        {
            DB::rollBack();
            alert()->error('شکست',);
        }
        alert()->success('موفق','پرمیشن مورد نظر ثبت شد');
        return redirect()->route('admin.permissions.index');
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
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit',compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name'=>'required',
            'display_name'=>'required'
        ]);

        try
        {
            DB::beginTransaction();
            $permission->update([
                'name'=>$request->name,
                'display_name'=>$request->display_name
            ]);
            DB::commit();
        }
        catch (\Exception $ex)
        {
            DB::rollBack();
            alert()->error('شکست',);
        }
        alert()->success('موفق','پرمیشن مورد نظر ویرایش شد');
        return redirect()->route('admin.permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
