@extends('admin.layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="d-flex justify-content-between mb-4">
                <h5 class="font-weight-bold">
                    لیست بنر ({{$banners->total()}})
                </h5>
                <a class="btn btn-sm btn-outline-primary" href="{{route('admin.banners.create')}}">
                    <i class="fa fa-plus"></i>
                    ایجاد بنر
                </a>
            </div>
            <div>
                <table class="table table-bordered table-striped text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>عکس</th>
                        <th>عنوان</th>
                        <th>متن</th>
                        <th>اولویت</th>
                        <th>وضعیت</th>
                        <th>نوع</th>
                        <th>متن دکمه</th>
                        <th>لینک دکمه</th>
                        <th>آیکون دکمه</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($banners as $key => $banner)
                        <tr>
                            <th>{{ $banners->firstItem() + $key  }}</th>
                            <th>
                                <a target="_blank" href="{{url('/upload/files/banners/images/'.$banner->image)}}">
                                    {{$banner->image}}
                                </a>
                            </th>
                            <th>{{$banner->title}}</th>
                            <th>{{$banner->text}}</th>
                            <th>{{$banner->priority}}</th>
                            <th>
                                <span class="{{ $banner->getRawOriginal('is_active') ? 'text-success' : 'text-danger'}}">
                                    {{$banner->is_active}}
                                </span>
                            </th>
                            <th>{{$banner->type}}</th>
                            <th>{{$banner->button_text}}</th>
                            <th>{{$banner->button_link}}</th>
                            <th>{{$banner->button_icon}}</th>
                            <th>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        عملیات
                                    </button>
                                    <div class="dropdown-menu">
                                        <form action="{{route('admin.banners.destroy',['banner'=>$banner->id])}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-right"> حذف </button>
                                        </form>
                                        <a href="{{ route('admin.banners.edit',['banner'=>$banner->id])  }}"
                                           class="dropdown-item text-right"> ویرایش </a>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{$banners->links()}}
            </div>

        </div>
    </div>
@endsection
