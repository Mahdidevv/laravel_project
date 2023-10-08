@extends('admin.layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="d-flex justify-content-between mb-4">
                <h5 class="font-weight-bold">
                    لیست کاربران ({{$users->total()}})
                </h5>
            </div>
            <div>
                <table class="table table-bordered table-striped text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>ایمیل</th>
                        <th>شماره همراه</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $key => $user)
                        <tr>
                            <th>{{ $users->firstItem() + $key  }}</th>
                            <th>{{ ($user->name) ? $user->name : 'بدون نام' }}</th>
                            <th>{{ ($user->email) ? $user->email : 'بدون ایمیل' }}</th>
                            <th>{{$user->cellphone}}</th>
                            <th>
                                <a class="btn btn-sm btn-outline-danger" href="{{route('admin.users.edit',['user'=>$user->id])}}"> ویرایش </a>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{$users->links()}}
            </div>

        </div>
    </div>
@endsection
