@extends('admin.layouts.admin')
@section('title')
    create users
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="mb-4">
                <h5 class="font-weight-bold">
                    ویرایش ویژگی
                </h5>
                <hr>
                @include('admin.sections.errors')
                <form action="{{route('admin.users.update',['user'=>$user->id])}}" method="post">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="name">نام</label>
                            <input class="form-control" name="name" type="text" value="{{$user->name}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="name">موبایل</label>
                            <input class="form-control" name="cellphone" type="text" value="{{$user->cellphone}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="exampleFormControlSelect1">نقش کاربر</label>
                            <select name="role" class="form-control" id="exampleFormControlSelect1">
                                <option></option>
                                @foreach($roles as $role)
                                    <option value="{{$role->name}}" {{in_array($role->id,$userRole->pluck('id')->toArray()) ? 'selected' : ''}}>{{$role->display_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="accordion col-md-12 mt-3" id="accordionPermission">
                            <div class="card">
                                <div class="card-header p-1" id="headingOne">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-right" type="button" data-toggle="collapse" data-target="#collapsePermission" aria-expanded="true" aria-controls="collapsePermission">
                                            مجوز های دسترسی
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapsePermission" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionPermission">
                                    <div class="card-body row">
                                        @foreach($permissions as $permission)
                                            <div class="form-group form-check col-md-3">
                                                <input class="form-check-input" type="checkbox" name="{{$permission->name}}" value="{{$permission->name}}" id="permissionCheckbox-{{$permission->id}}"
                                                    {{ (in_array($permission->id,$permissionUser->pluck('id')->toArray()) ? 'checked' : '') }}
                                                >
                                                <label class="form-check-label mr-4" for="permissionCheckbox-{{$permission->id}}">
                                                    {{$permission->display_name}}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-outline-danger mt-3" type="submit" >ویرایش</button>
                    <a href="{{route('admin.users.index')}}" class="btn btn-dark mt-3">بازگشت</a>
                </form>
            </div>
        </div>
    </div>
@endsection
