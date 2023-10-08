<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index',compact('users'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $userRole = $user->roles;
        $permissions = Permission::all();
        $permissionUser=$user->permissions;
        return view('admin.users.edit',compact('user','roles','userRole','permissions','permissionUser'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request , User $user)
    {
        $request->validate([
            'name'=>'nullable',
            'cellphone'=>'required'
        ]);
        try
        {
          DB::beginTransaction();
          $user->update([
                'name' => $request->name,
                'cellphone' => $request->cellphone
          ]);
          $permission = $request->except('_token','name','cellphone','_method','role');
          if ($request->role != null && $permission != null){
              $user->removeRole($request->role);
              $user->assignRole($request->role);
              $permissions = Permission::all();
              $user->revokePermissionTo($permissions);
              $user->givePermissionTo($permission);
          }
          elseif ($request->role == null && $permission != null)
          {
              alert()->warning('توجه','اول باید به کاربر مورد نظر یک نقش درنظر بگیرید');
              return redirect()->back();
          }
          DB::commit();
        }
        catch (\Exception $ex)
        {
            DB::rollBack();
            alert()->success('شکست',$ex->getMessage());
        }
        alert()->success('باتشکر','کاربر با موفقیت ویرایش شد');
        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
