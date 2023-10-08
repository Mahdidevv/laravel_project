@extends('admin.layouts.admin')
@section('title')
    create permissions
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="mb-4">
                <h5 class="font-weight-bold">
                    ایجاد پرمیشن
                </h5>
                <hr>
                @include('admin.sections.errors')
                <form action="{{route('admin.permissions.store')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="name">نام به انگلیسی</label>
                            <input class="form-control" name="name" type="text" value="{{old('name')}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="name">نام نمایشی (به فارسی )</label>
                            <input class="form-control" name="display_name" type="text" value="{{old('display_name')}}">
                        </div>
                    </div>
                    <button class="btn btn-outline-dark" type="submit" >ثبت</button>
                    <a href="{{route('admin.permissions.index')}}" class="btn btn-dark">بازگشت</a>
                </form>
            </div>
        </div>
    </div>
@endsection
