@extends('admin.layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="d-flex justify-content-between mb-4">
                <h5 class="font-weight-bold">
                    لیست نقش ها ({{$roles->total()}})
                </h5>
                <a class="btn btn-sm btn-outline-primary" href="{{route('admin.roles.create')}}">
                    <i class="fa fa-plus"></i>
                    ایجاد نقش
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
                    @foreach($roles as $key => $role)
                        <tr>
                            <th>{{ $roles->firstItem() + $key  }}</th>
                            <th>{{$role->name}}</th>
                            <th>{{$role->display_name}}</th>
                            <th>
                                <a class="btn btn-sm btn-outline-danger" href="{{route('admin.roles.edit',['role'=>$role->id])}}"> ویرایش </a>
                                <a class="btn btn-sm btn-outline-info" href="{{route('admin.roles.show',['role'=>$role->id])}}"> نمایش </a>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{$roles->links()}}
            </div>

        </div>
    </div>
@endsection
