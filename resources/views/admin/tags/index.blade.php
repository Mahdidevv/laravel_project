@extends('admin.layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="d-flex justify-content-between mb-4">
                <h5 class="font-weight-bold">
                    لیست برند ({{$tags->total()}})
                </h5>
                <a class="btn btn-sm btn-outline-primary" href="{{route('admin.tags.create')}}">
                    <i class="fa fa-plus"></i>
                    ایجاد تگ
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
                    @foreach($tags as $key => $tag)
                        <tr>
                            <th>{{ $tags->firstItem() + $key  }}</th>
                            <th>{{$tag->name}}</th>
                            <th>
                                <a class="btn btn-sm btn-outline-info" href="{{route('admin.tags.show',['tag'=>$tag->id])}}"> نمایش </a>
                                <a class="btn btn-sm btn-outline-danger" href="{{route('admin.tags.edit',['tag'=>$tag->id])}}"> ویرایش </a>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{$tags->links()}}
            </div>

        </div>
    </div>
@endsection
