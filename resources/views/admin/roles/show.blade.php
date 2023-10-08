@extends('admin.layouts.admin')
@section('title')
    create role
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="mb-4">
                <h5 class="font-weight-bold">
                     {{$role->name}}
                </h5>
                <hr>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>نام به انگلیسی</label>
                            <input class="form-control" value=" {{$role->name}} " disabled>
                        </div>

                        <div class="form-group col-md-3">
                            <label>نام نمایشی</label>
                            <input class="form-control" value=" {{$role->display_name}} " disabled>
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
                                        @foreach($rolePermissions as $permission)
                                            <div class="form-group form-check col-md-3">
                                                <input class="form-check-input" type="checkbox" id="permissionCheckbox-{{$permission->id}}" checked disabled>
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
                    <a href="{{route('admin.roles.index')}}" class="btn btn-dark mt-3">بازگشت</a>
            </div>
        </div>
    </div>
@endsection
