@extends('admin.layouts.admin')
@section('title')
    create tag
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="mb-4">
                <h5 class="font-weight-bold">
                    ایجاد تگ
                </h5>
                <hr>
                @include('admin.sections.errors')
                <form action="{{route('admin.tags.store')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="name">نام تگ</label>
                            <input class="form-control" name="name" id="name" type="text">
                        </div>
                    </div>
                    <button class="btn btn-outline-dark" type="submit" >ثبت</button>
                    <a href="{{route('admin.tags.index')}}" class="btn btn-dark">بازگشت</a>
                </form>
            </div>
        </div>
    </div>
@endsection
