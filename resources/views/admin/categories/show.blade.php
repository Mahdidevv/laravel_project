@extends('admin.layouts.admin')
@section('title')
    create brand
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="mb-4">
                <h5 class="font-weight-bold">
                     {{$category->name}}
                </h5>
                <hr>
                @include('admin.sections.errors')
                <form action="{{route('admin.categories.store')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>نام</label>
                            <input class="form-control" value=" {{$category->name}} " disabled>
                        </div>

                        <div class="form-group col-md-3">
                            <label>نام انگلیسی</label>
                            <input class="form-control" value=" {{$category->slug}} " disabled>
                        </div>

                        <div class="form-group col-md-3">
                            <label>والد</label>
                            <input class="form-control" value=" {{$category->parent->name ?? 'ندارد'}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>وضعیت</label>
                            <input class="form-control" value=" {{$category->is_active}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>آیکون</label>
                            <input class="form-control" value=" {{$category->icon}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>تاریخ ایجاد</label>
                            <input class="form-control" value=" {{verta($category->create_at)}} " disabled>
                        </div>
                        <div class="form-group col-md-12">
                            <label>وضعیت</label>
                            <input class="form-control" value=" {{$category->description}} " disabled>
                        </div>

                        <div class="col-md-12">
                            <hr>
                            <div class="row">

                                <div class="col-md-3">
                                    <label>ویژگی ها</label>
                                    <div class="form-control div-disable">
                                        @foreach($category->attributes as $attribute)
                                            {{ $attribute->name }} {{$loop->last ? '' : ' , ' }}
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label>ویژگی های قابل فیلتر</label>
                                    <div class="form-control div-disable">
                                        @foreach($category->attributes()->wherePivot('is_filter',1)->get() as $attribute)
                                            {{$attribute->name ?? 'ندارد'}} {{ @$loop->last == 'ندارد' ? '' : ' , ' }}
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label>ویژگی های متغییر </label>
                                    <div class="form-control div-disable">
                                        @foreach($category->attributes()->wherePivot('is_variation',1)->get() as $attribute)
                                            {{$attribute->name ?? 'ندارد'}} {{ @$loop->last == 'ندارد' ? '' : ' , ' }}
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <a href="{{route('admin.categories.index')}}" class="btn btn-dark mt-4">بازگشت</a>
                </form>
            </div>
        </div>
    </div>
@endsection
