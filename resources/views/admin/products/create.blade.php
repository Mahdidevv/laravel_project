@extends('admin.layouts.admin')
@section('title')
    create product
@endsection
@section('script')
    <script>
        $('#brandSelect').selectpicker({
            'title':'انتخاب برند'
        })
        $('#tagSelect').selectpicker({
            'title':'انتخاب تگ'
        })
        $('#primary_image').change(function (){
            let fileName = $(this).val();
            $('#primary_image').next('.custom-file-label').html(fileName);
        })
        $('#images').change(function (){
            let fileName = $(this).val();
            $('#images').next('.custom-file-label').html(fileName);
        })
        $('#categorySelect').selectpicker({
            'title':'انتخاب دسته بندی'
        })
        $('#attributesContainer').hide();
        $('#categorySelect').on('changed.bs.select', function() {
            let categoryId = $(this).val();

            $.get(`{{url('/admin-panel/management/category-attributes/${categoryId}')}}` , function(response , status){
                if(status == 'success'){
                    $('#attributesContainer').fadeIn();
                    $('#attributes').find('div').remove();

                    response.attributes.forEach(attribute =>{
                        let divParentAttributes = $('<div/>',{
                           class : 'form-group col-md-3'
                        });
                        let labelAttributes = $('<label/>',{
                            text : attribute.name,
                            for : attribute.name
                        });

                        divParentAttributes.append(labelAttributes);

                        let inputAttributes = $('<input/>',{
                            class : 'form-control',
                            text : 'text',
                            id : attribute.name,
                            name : `attributes_ids[${attribute.id}]`
                        });

                        divParentAttributes.append(inputAttributes);
                        $('#attributes').append(divParentAttributes);
                    });

                    $('#variationName').text(response.variation.name)
                }
            })

            // console.log(categoryId);
        });
        $("#czContainer").czMore();
    </script>

@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="mb-4">
                <h5 class="font-weight-bold">
                    ایجاد محصول
                </h5>
                <hr>
                @include('admin.sections.errors')
                <form action="{{route('admin.products.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="name">نام</label>
                            <input class="form-control" name="name" id="name" type="text">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="brand">نام</label>
                            <select class="form-control" name="brand_id" id="brandSelect">
                                @foreach($brands as $brand)
                                    <option value="{{$brand->id}}">
                                            {{$brand->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="name">وضعیت</label>
                            <select class="form-control" name="is_active" id="activeSelect">
                                    <option value="1" selected>فعال</option>
                                    <option value="0">غیر فعال</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="tags_id">تگ</label>
                            <select class="form-control" name="tag_ids[]" id="tagSelect" multiple >
                                @foreach($tags as $tag)
                                    <option value="{{$tag->id}}">
                                        {{$tag->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="description">توضیحات</label>
                            <textarea class="form-control" name="description" id="description">
                            </textarea>
                        </div>
                        {{--Section Select Page--}}
                        <div class="col-md-12" >
                            <hr>
                            <p>تصاویر محصول :</p>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="primary_image"> انتخاب تصویر اصلی </label>
                            <div class="custom-file">
                                <input type="file" name="primary_image" class="custom-file-input" id="primary_image">
                                <label class="custom-file-label" for="primary_image"> انتخاب فایل </label>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="images">انتخاب تصاویر</label>
                            <div class="custom-file">
                                <input type="file" name="images[]" class="custom-file-input" id="images" multiple>
                                <label class="custom-file-label" for="images">انتخاب فایل</label>
                            </div>
                        </div>
                        {{--Category&Attributes Product Section--}}
                        <div class="col-md-12" >
                            <hr>
                            <p>دسته بندی محصول :</p>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="brand">دسته بندی</label>
                            <select class="form-control" name="category_id" id="categorySelect">
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">
                                        {{$category->name}} - {{$category->parent->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{--Here Add Attributes With Js--}}
                        <div id="attributesContainer" class="col-md-12">
                            <div id="attributes" class="row">
                            </div>
                            <div class="col-md-12">
                                <hr>
                                <p>
                                    افزودن قیمت و موجودی برای متغیر <span class="font-weight-bold" id="variationName"></span> :
                                </p>
                                <div id="czContainer">
                                    <div id="first">
                                        <div class="recordset">
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <label for="value">نام</label>
                                                    <input class="form-control" name="variation_values[value][]" type="text">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="value">قیمت</label>
                                                    <input class="form-control" name="variation_values[price][]" type="text">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="value">تعداد</label>
                                                    <input class="form-control" name="variation_values[quantity][]" type="text">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="value">شناسه انبار</label>
                                                    <input class="form-control" name="variation_values[sku][]" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{--Delivery Section--}}
                        <div class="col-md-12" >
                            <hr>
                            <p>هزینه ارسال :</p>
                        </div>
                        <div class="form-group col-md-3">
                        <label for="delivery_amount">هزینه ارسال</label>
                        <input class="form-control" name="delivery_amount" id="delivery_amount" type="text">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="delivery_amount_per_product">هزینه ارسال به ازای هر محصول اضافی</label>
                            <input class="form-control" name="delivery_amount_per_product" id="delivery_amount_per_product" type="text">
                        </div>
                    </div>






                            <button class="btn btn-outline-dark mt-5" type="submit" >ثبت</button>
                            <a href="{{route('admin.products.index')}}" class="btn btn-dark mt-5">بازگشت</a>


                </form>
            </div>
        </div>
    </div>
@endsection
