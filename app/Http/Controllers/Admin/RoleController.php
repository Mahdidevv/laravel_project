<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::latest()->paginate();
        return view('admin.roles.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $permissions = Permission::all();
       return view('admin.roles.create',compact('permissions'));
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
           $role = Role::create([
                'name'=>$request->name,
                'display_name'=>$request->display_name
            ]);
           $permission = $request->except('_token','name','display_name');
            $role->givePermissionTo($permission);
            DB::commit();
        }
        catch (\Exception $ex)
        {
            DB::rollBack();
            alert()->error('شکست',$ex->getMessage());
        }
        alert()->success('موفق','نقش مورد نظر ثبت شد');
//        return redirect()->route('admin.roles.index');
        return redirect()->route('admin.roles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $rolePermissions = $role->permissions;
        return view('admin.roles.show',compact('role','rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $rolePermissions = $role->permissions;
        $permissions = Permission::all();
        return view('admin.roles.edit',compact('role','rolePermissions','permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'=>'required',
            'display_name'=>'required'
        ]);

        try
        {
            DB::beginTransaction();
            $role->update([
                'name'=>$request->name,
                'display_name'=>$request->display_name
            ]);
            $permission = $request->except('_token','name','display_name','_method');
            $role->revokePermissionTo($role->permissions);
            $role->givePermissionTo($permission);
            DB::commit();
        }
        catch (\Exception $ex)
        {
            DB::rollBack();
            alert()->error('شکست',$ex->getMessage());
        }
        alert()->success('موفق','نقش مورد نظر ثبت شد');
        return redirect()->route('admin.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
