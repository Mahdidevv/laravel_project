@extends('admin.layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="d-flex justify-content-between mb-4">
                <h5 class="font-weight-bold">
                    لیست برند ({{$categories->total()}})
                </h5>
                <a class="btn btn-sm btn-outline-primary" href="{{route('admin.categories.create')}}">
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
                        <th>نام انگلیسی</th>
                        <th>والد</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $key => $category)
                        <tr>
                            <th>{{ $categories->firstItem() + $key  }}</th>
                            <th>{{$category->name}}</th>
                            <th>{{$category->slug}}</th>
                            <th>
                                @if($category->parent_id == 0)
                                    {{"ندارد"}}
                                @else
                                    {{ $category->parent->name ?? 'None'}}
                                @endif

                            </th>
                            <th>
                                <span class="{{$category->getRawOriginal('is_active') ? 'text-success':'text-danger'}}">
                                    {{$category->is_active}}
                                </span>
                            </th>

                            <th>
                                <a class="btn btn-sm btn-outline-info" href="{{route('admin.categories.show',['category'=>$category->id])}}"> نمایش </a>
                                <a class="btn btn-sm btn-outline-danger" href="{{route('admin.categories.edit',['category'=>$category->id])}}"> ویرایش </a>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{$categories->links()}}
            </div>

        </div>
    </div>
@endsection
