@extends('admin.layouts.admin')
@section('title')
    create brand
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
                    ایجاد بنر
                </h5>
                <hr>
                @include('admin.sections.errors')
                <form action="{{route('admin.banners.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="primary_image"> انتخاب تصویر اصلی </label>
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="image">
                                <label class="custom-file-label" for="image"> انتخاب فایل </label>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="title">عنوان</label>
                            <input class="form-control" name="title" id="title" type="text">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="text">متن</label>
                            <input class="form-control" name="text" id="text" type="text">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="priority">اولویت</label>
                            <input class="form-control" name="priority" id="priority" type="number">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="is_active">وضعیت</label>
                            <select class="form-control" name="is_active" id="is_active">
                                <option value="1" selected>فعال</option>
                                <option value="0">غیر فعال</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="type">نوع بنر</label>
                            <input class="form-control" name="type" id="type" type="text">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="btn_text">متن دکمه</label>
                            <input class="form-control" name="button_text" id="btn_text" type="text">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="btn_link">لینک دکمه</label>
                            <input class="form-control" name="button_link" id="btn_link" type="text">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="btn_icon">آیکون دکمه</label>
                            <input class="form-control" name="button_icon" id="btn_icon" type="text">
                        </div>
                    </div>
                    <button class="btn btn-outline-dark" type="submit" >ثبت</button>
                    <a href="{{route('admin.banners.index')}}" class="btn btn-dark">بازگشت</a>
                </form>
            </div>
        </div>
    </div>
@endsection
