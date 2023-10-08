@extends('admin.layouts.admin')
@section('title')
    create brand
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="mb-4">
                <h5 class="font-weight-bold">
                    ویرایش برند
                </h5>
                <hr>
                @include('admin.sections.errors')
                <form action="{{route('admin.brands.update',['brand'=>$brand->id])}}" method="post">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="name">نام برند</label>
                            <input class="form-control" name="name" id="name" type="text" value="{{$brand->name}}">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="is_active">وضعیت</label>
                            <select class="form-control" name="is_active" id="is_active">
                                @if($brand->getRawOriginal('is_active'))
                                    <option value="1" selected>فعال</option>
                                    <option value="0" >غیر فعال</option>
                                @else
                                    <option value="1">فعال</option>
                                    <option value="0" selected>غیر فعال</option>
                                @endif


                            </select>
                        </div>
                    </div>
                    <button class="btn btn-outline-danger" type="submit" >ویرایش</button>
                    <a href="{{route('admin.brands.index')}}" class="btn btn-dark">بازگشت</a>
                </form>
            </div>
        </div>
    </div>
@endsection
