@extends('admin.layouts.admin')
@section('title')
    edit tag
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="mb-4">
                <h5 class="font-weight-bold">
                    ویرایش تگ
                </h5>
                <hr>
                @include('admin.sections.errors')
                <form action="{{route('admin.tags.update',['tag'=>$tag->id])}}" method="post">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="name">نام تگ</label>
                            <input class="form-control" name="name" id="name" type="text" value="{{$tag->name}}">
                        </div>
                    </div>
                    <button class="btn btn-outline-danger" type="submit" >ویرایش</button>
                    <a href="{{route('admin.tags.index')}}" class="btn btn-dark">بازگشت</a>
                </form>
            </div>
        </div>
    </div>
@endsection
