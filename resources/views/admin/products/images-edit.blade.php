@extends('admin.layouts.admin')
@section('title')
    edit product
@endsection
@section('script')
    <script>
        $('#primary_image').change(function (){
            let fileName = $(this).val();
            $('#primary_image').next('.custom-file-label').html(fileName);
        })
        $('#images').change(function (){
            let fileName = $(this).val();
            $('#images').next('.custom-file-label').html(fileName);
        })
    </script>
@endsection
@section('script')
    <script>
        $('#brandSelect').selectpicker({
            'title':'انتخاب برند'
        });
        $('#tagSelect').selectpicker({
            'title':'انتخاب تگ'
        });




            </script>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="mb-4">
                <h5 class="font-weight-bold">
                     ویرایش تصاویر محصول : {{$product->name}}
                </h5>
                <hr>
                @include('admin.sections.errors')

                <div class="row">
                    <div class="col-12 col-md-12 mb-5">
                            <h5>
                                تصویر اصلی
                            </h5>
                    </div>
                    <div class="col-12 col-md-3 mb-5">
                        <div class="card">
                            <img class="card-img-top" src="{{url('/upload/files/products/images/'.$product->primary_image)}}" alt="{{$product->name}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">

        <div class="col-12 col-md-12 mb-5">
            <h5>
                سایر تصاویر
            </h5>
        </div>
        @foreach($images as $image)
            <div class="col-md-3">
                <div class="card">
                    <img class="card-img-top" src="{{url('/upload/files/products/images/'.$image->image)}}" alt="{{$product->name}}">
                    <div class="card-body text-center">
                        <form action="{{route('admin.products.delete.image',['product'=> $product->id])}}" method="post">
                            @csrf
                            @method('delete')
                            <input type="hidden" name="image_id" value="{{$image->id}}">
                            <button type="submit" class="btn btn-sm btn-danger mb-5">حذف</button>
                        </form>
                        <form action="{{route('admin.products.set.primary.image',['product'=>$product->id])}}" method="post">
                            @csrf
                            @method('put')
                            <input type="hidden" name="image_id" value="{{$image->id}}">
                            <button type="submit" class="btn btn-sm btn-primary mb-5">انتخاب به عنوان تصویر اصلی</button>
                        </form>
                    </div>

                </div>

            </div>
        @endforeach
        <div class="col-md-12" >
            <hr>
            <p>تصاویر محصول :</p>
        </div>
        <form action="{{route('admin.products.add.image',['product'=>$product->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="form-group col-md-5">
                    <label for="primary_image"> انتخاب تصویر اصلی </label>
                    <div class="custom-file">
                        <input type="file" name="primary_image" class="custom-file-input" id="primary_image">
                        <label class="custom-file-label" for="primary_image"> انتخاب فایل </label>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for="images">انتخاب تصاویر</label>
                    <div class="custom-file">
                        <input type="file" name="images[]" class="custom-file-input" id="images" multiple>
                        <label class="custom-file-label" for="images">انتخاب فایل</label>
                    </div>
                </div>
            </div>
            <button class="btn btn-outline-primary mt-2" type="submit">ویرایش</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-dark mt-2 mr-3">بازگشت</a>
        </form>

    </div>
@endsection
