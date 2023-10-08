@extends('admin.layouts.admin')
@section('title')
    edit product
@endsection
@section('script')
    <script>
        $('#brandSelect').selectpicker({
            'title':'انتخاب برند'
        });
        $('#tagSelect').selectpicker({
            'title':'انتخاب تگ'
        });

        let variations = @json($productVariations);
        variations.forEach(variation => {
            $(`#dateOnSaleFrom-${variation.id}`).MdPersianDateTimePicker({
            targetDateSelector : `#dateOnSaleFromInput-${variation.id}`,
            englishNumber:false,
            enableTimePicker: true,
            textFormat: 'yyyy-MM-dd HH:mm:ss',
        });
        $(`#dateOnSaleTo-${variation.id}`).MdPersianDateTimePicker({
            targetDateSelector : `#dateOnSaleToInput-${variation.id}`,
            englishNumber:false,
            enableTimePicker: true,
            textFormat: 'yyyy-MM-dd HH:mm:ss',
        });
        });

            </script>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="mb-4">
                <h5 class="font-weight-bold">
                    ویرایش محصول
                </h5>
                <hr>
                @include('admin.sections.errors')
                <form action="{{route('admin.products.update',['product'=>$product->id])}}" method="post">
                    @csrf
                    @method('put')
                    <div class="row">

                        <div class="form-group col-md-3">
                            <label for="name">نام</label>
                            <input class="form-control" name="name" id="name" type="text" value="{{$product->name}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="brand">برند</label>
                            <select class="form-control" name="brand_id" id="brandSelect">
                                @foreach($brands as $brand)
                                    <option value="{{$brand->id}}" {{($brand->id == $product->brand_id) ? 'selected' : ''}}>
                                        {{$brand->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="name">وضعیت</label>
                            <select class="form-control" name="is_active" id="activeSelect">
                                <option value="1" {{$product->getRawOriginal('is_active') ? 'selected' : ''}}>فعال</option>
                                <option value="0" {{$product->getRawOriginal('is_active') ? '' : 'selected'}} >غیر فعال</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="tags_id">تگ</label>
                            <select class="form-control" name="tag_ids[]" id="tagSelect" multiple >
                                @foreach($tags as $tag)
                                    <option value="{{$tag->id}}" {{ in_array($tag->id,$productTags) ? 'selected' : '' }} >
                                        {{$tag->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="description">توضیحات</label>
                            <textarea rows="4" class="form-control" name="description" id="description">{{$product->description}}</textarea>
                        </div>

                        {{--Delivery Section--}}
                        <div class="col-md-12" >
                            <hr>
                            <p>هزینه ارسال :</p>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="delivery_amount">هزینه ارسال</label>
                            <input class="form-control" name="delivery_amount" id="delivery_amount" type="text" value="{{$product->delivery_amount}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="delivery_amount_per_product">هزینه ارسال به ازای هر محصول اضافی</label>
                            <input class="form-control" name="delivery_amount_per_product" id="delivery_amount_per_product" type="text" value="{{$product->delivery_amount_per_product}}">
                        </div>
{{--                        show Attribute--}}
                        <div class="col-md-12">
                            <hr>
                            <p class="font-weight-bold">
                                ویژگی ها
                            </p>
                        </div>
                        @foreach($productAttributes as $productAttribute)
                            <div class="form-group col-md-3">
                                <label>{{ $productAttribute->attribute->name }}</label>
                                <input class="form-control" name="attribute_values[{{$productAttribute->id}}]" value=" {{ $productAttribute->value }} ">
                            </div>
                        @endforeach
                        {{--                        show variations--}}
                        @foreach ($productVariations as $variation)
                            <div class="col-md-12">
                                <hr>
                                <div class="d-flex">
                                    <p class="mb-0"> قیمت و موجودی برای متغیر ( {{ $variation->value }} ) : </p>
                                    <p class="mb-0 mr-3">
                                        <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse"
                                                data-target="#collapse-{{ $variation->id }}">
                                            نمایش
                                        </button>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="collapse mt-2" id="collapse-{{ $variation->id }}">
                                    <div class="card card-body">
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label> قیمت </label>
                                                <input type="text"  class="form-control" value="{{ $variation->price }}"  name="variation_values[{{$variation->id}}][price]">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label> تعداد </label>
                                                <input type="text"  class="form-control" name="variation_values[{{$variation->id}}][quantity]" value="{{ $variation->quantity }}">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label> sku </label>
                                                <input type="text"  class="form-control" name="variation_values[{{$variation->id}}][sku]" value="{{ $variation->sku }}">
                                            </div>

                                            {{-- Sale Section --}}
                                            <div class="col-md-12">
                                                <p> حراج : </p>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label> قیمت حراجی </label>
                                                <input type="text" value="{{ $variation->sale_price }}" name="variation_values[{{$variation->id}}][sale_price]" class="form-control">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label> تاریخ شروع حراجی </label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend order-2">
                                                        <span class="input-group-text" id="dateOnSaleFrom-{{$variation->id}}">
                                                            <i class="fas fa-clock"></i>
                                                        </span>
                                                    </div>
                                                    <input id="dateOnSaleFromInput-{{$variation->id}}"
                                                           type="text"
                                                           name="variation_values[{{$variation->id}}][date_on_sale_from]"
                                                           value="{{ ($variation->date_on_sale_to == null) ? null : verta($variation->date_on_sale_from)}}"
                                                           class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label> تاریخ پایان حراجی </label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend order-2">
                                                        <span class="input-group-text" id="dateOnSaleTo-{{$variation->id}}">
                                                            <i class="fas fa-clock"></i>
                                                        </span>
                                                    </div>
                                                    <input id="dateOnSaleToInput-{{$variation->id}}"
                                                           type="text" name="variation_values[{{$variation->id}}][date_on_sale_to]"
                                                           value="{{ $variation->date_on_sale_to == null ? null : verta($variation->date_on_sale_to)}}"
                                                           class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach


{{--($variation->date_on_sale_from == null) ? '' :--}}

                    </div>

                    <button class="btn btn-outline-danger mt-4" type="submit" >ویرایش</button>
                    <a href="{{route('admin.products.index')}}" class="btn btn-dark mt-4">بازگشت</a>
                </form>
            </div>
        </div>
    </div>
@endsection
