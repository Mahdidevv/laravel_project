@extends('admin.layouts.admin')
@section('title')
    show tag
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="mb-4">
                <h5 class="font-weight-bold">
                     {{$tag->name}}
                </h5>
                <hr>
                @include('admin.sections.errors')
                <form action="{{route('admin.tags.store')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>نام تگ</label>
                            <input class="form-control" value=" {{$tag->name}} " disabled>
                        </div>

                        <div class="form-group col-md-3">
                            <label>زمان ثبت</label>
                            <input class="form-control" value=" {{verta($tag->create_at)}} " disabled>
                        </div>
                    </div>
                    <a href="{{route('admin.tags.index')}}" class="btn btn-dark">بازگشت</a>
                </form>
            </div>
        </div>
    </div>
@endsection
