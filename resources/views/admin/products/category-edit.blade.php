@extends('admin.layouts.admin')
@section('title')
    create product
@endsection
@section('script')
    <script>

        $('#categorySelect').selectpicker({
            'title': 'انتخاب دسته بندی'
        })
        $('#attributesContainer').hide();
        $('#categorySelect').on('changed.bs.select', function () {
            let categoryId = $(this).val();

            $.get(`{{url('/admin-panel/management/category-attributes/${categoryId}')}}`, function (response, status) {
                if (status == 'success') {
                    $('#attributesContainer').fadeIn();
                    $('#attributes').find('div').remove();

                    response.attributes.forEach(attribute => {
                        let divParentAttributes = $('<div/>', {
                            class: 'form-group col-md-3'
                        });
                        let labelAttributes = $('<label/>', {
                            text: attribute.name,
                            for: attribute.name
                        });

                        divParentAttributes.append(labelAttributes);

                        let inputAttributes = $('<input/>', {
                            class: 'form-control',
                            text: 'text',
                            id: attribute.name,
                            name: `attributes_ids[${attribute.id}]`
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
                    ویرایش دسته بندی : {{$product->name}}
                </h5>
                <hr>
                @include('admin.sections.errors')
                <form action="{{route('admin.products.update.category',['product'=>$product->id])}}" method="post">
                    @csrf
                    @method('put')
                    <div class="form-row">
                        {{--Category&Attributes Product Section--}}
                        <div class="form-group col-md-3">
                            <label for="brand">دسته بندی</label>
                            <select class="form-control" name="category_id" id="categorySelect">
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}" {{$category->id == $product->category_id ? 'selected' : ''}}>
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
                                    افزودن قیمت و موجودی برای متغیر <span class="font-weight-bold"
                                                                          id="variationName"></span> :
                                </p>
                                <div id="czContainer">
                                    <div id="first">
                                        <div class="recordset">
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <label for="value">نام</label>
                                                    <input class="form-control" name="variation_values[value][]"
                                                           type="text">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="value">قیمت</label>
                                                    <input class="form-control" name="variation_values[price][]"
                                                           type="text">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="value">تعداد</label>
                                                    <input class="form-control" name="variation_values[quantity][]"
                                                           type="text">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="value">شناسه انبار</label>
                                                    <input class="form-control" name="variation_values[sku][]"
                                                           type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <button class="btn btn-outline-dark mt-5" type="submit">ثبت</button>
                    <a href="{{route('admin.products.index')}}" class="btn btn-dark mt-5">بازگشت</a>


                </form>
            </div>
        </div>
    </div>
@endsection
