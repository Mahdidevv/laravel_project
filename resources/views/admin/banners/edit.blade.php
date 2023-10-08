@extends('admin.layouts.admin')
@section('title')
    edit banne
@endsection
@section('script')
    <script>
        $('#image').change(function (){
            let fileName = $(this).val();
            $('#image').next('.custom-file-label').html(fileName);
        })
    </script>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="mb-4">
                <h5 class="font-weight-bold">
                    ویرایش بنر
                </h5>
                <hr>
                @include('admin.sections.errors')
                <form action="{{route('admin.banners.update',['banner'=>$banner->id])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row justify-content-center mb-4">
                        <div class="col-md-4">
                            <div class="card">
                                <img class="card-img-top" src="{{url('/upload/files/banners/images/'.$banner->image)}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="image"> انتخاب تصویر اصلی </label>
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="image">
                                <label class="custom-file-label" for="image"> انتخاب فایل </label>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="title">عنوان</label>
                            <input class="form-control" name="title" id="title" type="text" value="{{$banner->title}}">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="text">متن</label>
                            <input class="form-control" name="text" id="text" type="text" value="{{$banner->text}}">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="priority">اولویت</label>
                            <input class="form-control" name="priority" id="priority" type="number" value="{{$banner->priority}}">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="is_active">وضعیت</label>
                            <select class="form-control" name="is_active" id="is_active">
                                <option value="1" value="{{ $banner->getRawOriginal('is_active') ? 'selected' : ''  }}" >فعال</option>
                                <option value="0" value="{{ $banner->getRawOriginal('is_active') ? '' : 'selected'  }}">غیر فعال</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="type">نوع بنر</label>
                            <input class="form-control" name="type" id="type" type="text" value="{{$banner->type}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="button_text">متن دکمه</label>
                            <input class="form-control" name="button_text" id="button_text" type="text" value="{{$banner->button_text}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="button_link">لینک دکمه</label>
                            <input class="form-control" name="button_link" id="button_link" type="text" value="{{$banner->button_link}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="button_icon">آیکون دکمه</label>
                            <input class="form-control" name="button_icon" id="button_icon" type="text" value="{{$banner->button_icon}}">
                        </div>
                    </div>

                    <button class="btn btn-outline-dark" type="submit" >ثبت</button>
                    <a href="{{route('admin.banners.index')}}" class="btn btn-dark">بازگشت</a>
                </form>
            </div>
        </div>
    </div>
@endsection
