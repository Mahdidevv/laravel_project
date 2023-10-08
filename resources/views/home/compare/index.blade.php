@extends('home.layouts.home')
@section('title')
    صفحه مقایسه
@endsection
@section('content')
    <div class="breadcrumb-area pt-35 pb-35 bg-gray" style="direction: rtl;">
        <div class="container">
            <div class="breadcrumb-content text-center">
                <ul>
                    <li>
                        <a href="{{route('index')}}"> صفحه ای اصلی </a>
                    </li>
                    <li class="active"> مقایسه محصول</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- compare main wrapper start -->
    <div class="compare-page-wrapper pt-100 pb-100" style="direction: rtl;">
        <div class="container">
            <div class="row">
                <div class="col-lg-24 justify-content-center">
                    <!-- Compare Page Content Start -->
                    <div class="compare-page-content-wrap">
                        <div class="compare-table table-responsive">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                <tr>
                                    <td class="first-column"> محصول</td>
                                    @foreach($products as $product)
                                        <td class="product-image-title">
                                            <a href="single-product.html" class="image">
                                                <img class="img-fluid w-50"
                                                     src="{{url(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH').$product->primary_image)}}"
                                                     alt="Compare Product">
                                            </a>
                                            <a href="{{route('home.categories.show',['category'=>$product->category->slug])}}"
                                               class="category text-right text-center">
                                                {{$product->category->parent->name}}
                                            </a>
                                            <a href="{{route('home.products.show',['product'=>$product->slug])}}"
                                               class="title">
                                                {{$product->name}}
                                            </a>
                                        </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="first-column"> توضیحات</td>
                                    @foreach($products as $product)
                                        <td class="pro-desc ">
                                            <p class="text-right text-center ">
                                                {{ $product->description}}
                                            </p>
                                        </td>
                                    @endforeach
                                </tr>

                                <tr>
                                    <td class="first-column"> ویژگی متغییر</td>
                                    @foreach($products as $product)
                                        <td class="pro-color">
                                            <ul class="text-right text-center">
                                                <li>
                                                    {{$product->variations->first()->attribute->name}}
                                                    :
                                                    @foreach($product->variations as $productVariation)
                                                        <span> {{ $productVariation->value }} {{ $loop->last ? '' : '،' }}</span>
                                                    @endforeach
                                                </li>
                                            </ul>
                                        </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="first-column"> ویژگی</td>
                                    @foreach($products as $product)
                                        <td class="pro-stock">
                                            @foreach($product->attributes as $attributes)
                                                - {{$attributes->attribute->name}}
                                                :
                                                {{$attributes->value}}
                                                <br>
                                            @endforeach
                                        </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="first-column"> امتیاز</td>
                                    @foreach($products as $product)
                                        <td>
                                            <div
                                                 data-rating-value="{{ ceil($product->rates->avg('rate')) }}"
                                                 data-rating-stars="5"
                                                 data-rating-readonly="true">
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>

                                         <tr>
                                            <td class="first-column"> حذف</td>
                                                @foreach($products as $product)
                                                <td class="pro-remove">
                                                    <a href="{{route('home.compare.remove',['product'=>$product->id])}}">
                                                      <i class="sli sli-trash"></i>
                                                    </a>
                                                </td>
                                              @endforeach
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Compare Page Content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- compare main wrapper end -->
@endsection
