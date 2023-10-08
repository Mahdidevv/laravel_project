@extends('admin.layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="d-flex justify-content-between mb-4">
                <h5 class="font-weight-bold">
                    لیست پرمیشن ها ({{$permissions->total()}})
                </h5>
                <a class="btn btn-sm btn-outline-primary" href="{{route('admin.permissions.create')}}">
                    <i class="fa fa-plus"></i>
                    ایجاد پرمیشن
                </a>
            </div>
            <div>
                <table class="table table-bordered table-striped text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>نام نمایشی</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($permissions as $key => $permission)
                        <tr>
                            <th>{{ $permissions->firstItem() + $key  }}</th>
                            <th>{{$permission->name}}</th>
                            <th>{{$permission->display_name}}</th>
                            <th>
                                <a class="btn btn-sm btn-outline-danger" href="{{route('admin.permissions.edit',['permission'=>$permission->id])}}"> ویرایش </a>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{$permissions->links()}}
            </div>

        </div>
    </div>
@endsection
