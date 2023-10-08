@extends('admin.layouts.admin')
@section('title')
    create brand
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="mb-4">
                <h5 class="font-weight-bold">
                    ایجاد برند
                </h5>
                <hr>
                @include('admin.sections.errors')
                <form action="{{route('admin.brands.store')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="name">نام برند</label>
                            <input class="form-control" name="name" id="name" type="text">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="is_active">وضعیت</label>
                            <select class="form-control" name="is_active" id="is_active">
                                <option value="1" selected>فعال</option>
                                <option value="0">غیر فعال</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-outline-dark" type="submit" >ثبت</button>
                    <a href="{{route('admin.brands.index')}}" class="btn btn-dark">بازگشت</a>
                </form>
            </div>
        </div>
    </div>
@endsection
