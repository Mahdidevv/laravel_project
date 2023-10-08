@extends('admin.layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="d-flex justify-content-between mb-4">
                <h5 class="font-weight-bold">
                    لیست ویژگی ({{$attributes->total()}})
                </h5>
                <a class="btn btn-sm btn-outline-primary" href="{{route('admin.attributes.create')}}">
                    <i class="fa fa-plus"></i>
                    ایجاد ویژگی
                </a>
            </div>
            <div>
                <table class="table table-bordered table-striped text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($attributes as $key => $attribute)
                        <tr>
                            <th>{{ $attributes->firstItem() + $key  }}</th>
                            <th>{{$attribute->name}}</th>
                            <th>
                                <a class="btn btn-sm btn-outline-info" href="{{route('admin.attributes.show',['attribute'=>$attribute->id])}}"> نمایش </a>
                                <a class="btn btn-sm btn-outline-danger" href="{{route('admin.attributes.edit',['attribute'=>$attribute->id])}}"> ویرایش </a>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{$attributes->links()}}
            </div>

        </div>
    </div>
@endsection
