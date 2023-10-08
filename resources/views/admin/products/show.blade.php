@extends('admin.layouts.admin')
@section('title')
    show product
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="mb-4">
                <h5 class="font-weight-bold">
                     {{$product->name}}
                </h5>
                <hr>
                @include('admin.sections.errors')
                <form action="{{route('admin.products.store')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>نام محصول</label>
                            <input class="form-control" value=" {{$product->name}} " disabled>
                        </div>

                        <div class="form-group col-md-3">
                            <label>نام برند</label>
                            <input class="form-control" value=" {{$product->brand->name}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>نام دسته بندی</label>
                            <input class="form-control" value=" {{$product->category->name}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>وضعیت</label>
                            <input class="form-control" value=" {{$product->is_active}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>تگ ها</label>

                                <input class="form-control" value="@foreach($productTags as $tagSelected) {{$tagSelected->name}} {{$loop->last ? '' : ','}} @endforeach " disabled>

                        </div>
                        <div class="form-group col-md-3">
                            <label>زمان ایجاد</label>
                            <input class="form-control" value=" {{verta($product->create_at)}} " disabled>
                        </div>
                        <div class="form-group col-md-12">
                            <label>توضیحات</label>
                            <textarea class="form-control" rows="3" disabled>{{$product->description}}</textarea>
                        </div>
{{--                        Section Delivery--}}
                        <div class="col-md-12">
                            <hr>
                            <p>هزینه ارسال :</p>
                        </div>
                        <div class="form-group col-md-3">
                            <label>هزینه ارسال</label>
                            <input class="form-control" value=" {{$product->delivery_amount}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>هزینه ارسال به ازای هر محصول اضافی</label>
                            <input class="form-control" value=" {{$product->delivery_amount_per_product}} " disabled>
                        </div>
{{--                        Section Attributes And Variation--}}
                        <div class="col-md-12">
                            <hr>
                            <p>هزینه ارسال :</p>
                        </div>
                        @foreach($productAttributes as $productAttribute)
                            <div class="form-group col-md-3">
                                <label>{{ $productAttribute->attribute->name }}</label>
                                <input class="form-control" value=" {{ $productAttribute->value }} " disabled>
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
                                                <input type="text" disabled class="form-control" value="{{ $variation->price }}">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label> تعداد </label>
                                                <input type="text" disabled class="form-control" value="{{ $variation->quantity }}">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label> sku </label>
                                                <input type="text" disabled class="form-control" value="{{ $variation->sku }}">
                                            </div>

                                            {{-- Sale Section --}}
                                            <div class="col-md-12">
                                                <p> حراج : </p>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label> قیمت حراجی </label>
                                                <input type="text" value="{{ $variation->sale_price }}" disabled
                                                       class="form-control">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label> تاریخ شروع حراجی </label>
                                                <input type="text"
                                                       value="{{ $variation->date_on_sale_from == null ? null : verta($variation->date_on_sale_from) }}"
                                                       disabled class="form-control">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label> تاریخ پایان حراجی </label>
                                                <input type="text"
                                                       value="{{ $variation->date_on_sale_to == null ? null : verta($variation->date_on_sale_to) }}"
                                                       disabled class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    {{--  primary image--}}
                        <div class="col-md-12">
                            <hr>
                            <p class="font-weight-bolder">
                                تصویر اصلی
                            </p>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <img class="card-img-top" src="{{url('/upload/files/products/images/').'/'.$product->primary_image}}" alt="{{$product->name}}"
                                >
                            </div>
                        </div>
                    {{--other images--}}
                        <div class="col-md-12">
                          <hr>
                            <p class="font-weight-bold">
                                سایر تصاویر محصول
                            </p>
                        </div>
                        @foreach($productImages as $image)
                            <div class="col-md-3">
                                <div class="card">
                                    <img class="card-img-top" src="{{url('/upload/files/products/images/').'/'.$image->image}}" alt="{{$product->name}}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{route('admin.products.index')}}" class="btn btn-dark mt-3">بازگشت</a>
                </form>
            </div>
        </div>
    </div>
@endsection
