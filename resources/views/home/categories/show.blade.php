@extends('home.layouts.home')
@section('title')
    صفحه فروشگاه
@endsection

@section('script')
    <script>
        function filter(){

            let attributes = @json($attributes);

            attributes.map(attribute => {

                let attributeValue = $(`.attribute-${attribute.id}:checked`).map(function () {
                    return this.value;
                }).get().join('-');

                if (attributeValue == "")
                {
                    $(`#attribute-input-${attribute.id}`).prop('disabled',true);
                }
                else
                {
                    $(`#attribute-input-${attribute.id}`).val(attributeValue);
                }

            });

           let variation =$('.variation:checked').map(function () {
               return this.value;
           }).get().join('-');
           if (variation == "")
           {
               $('#variation-input').prop('disabled',true);
           }else
           {
               $('#variation-input').val(variation);
           }

           let sortByValue = $('#sort-by-select').val();
           if ( sortByValue == 'default')
           {
               $('#sort-by-input').prop('disabled',true);
           }
           else
           {
               $('#sort-by-input').val(sortByValue)
           }

           let searchValue = $('#searchProduct').val();
           console.log(searchValue);
           if (searchValue == '')
           {
               $('#search-input').prop('disabled',true);
           }
           else
           {
               $('#search-input').val(searchValue)
           }
           $('#filter-form').submit();
        }

        $('#filter-form').on('submit',function (event) {
            event.preventDefault();

            let currentUrl = '{{url()->current()}}';
            let url = currentUrl + '?' + decodeURIComponent($(this).serialize());
            $(location).attr('href',url);
        })
        $('.variation-select').on('change',function (){
            let variation = JSON.parse(this.value);
            let variationDiv = $('.variation-div')
            variationDiv.empty();
            $('.quantity-input').attr('data-max',1);
            $('.quantity-input').attr('data-max',variation.quantity);
            if (variation.is_sale)
            {
                console.log(toPersianNum(number_format(variation.price)));
                let spanSalePrice = $('<span />' , {
                    class : 'new',
                    text : toPersianNum(number_format(variation.sale_price)) + ' تومان'
                });
                let spanPrice = $('<span />' , {
                    class : 'old',
                    text : toPersianNum(number_format(variation.price)) + ' تومان'
                });
                variationDiv.append(spanSalePrice);
                variationDiv.append(spanPrice);
            }
            else
            {
                let spanPrice = $('<span />' , {
                    class : 'new',
                    text : toPersianNum(number_format(variation.price)) + ' تومان'
                });
                variationDiv.append(spanPrice);
            }
        });

        $('#pagination li a').map(function (){
            if($(this).attr('href') != undefined)
            {
                let decodedUrl = decodeURIComponent($(this).attr('href'));
                $(this).attr('href',decodedUrl)
            }

        });
    </script>
@endsection
@section('content')
    <div class="breadcrumb-area pt-35 pb-35 bg-gray" style="direction: rtl;">
        <div class="container">
            <div class="breadcrumb-content text-center">
                <ul>
                    <li>
                        <a href="{{route('index')}}">صفحه ای اصلی</a>
                    </li>
                    <li class="active">فروشگاه </li>
                </ul>
            </div>
        </div>
    </div>


        <div class="shop-area pt-95 pb-100">
            <div class="container">
                <div class="row flex-row-reverse text-right">

                    <!-- sidebar -->
                    <div class="col-lg-3 order-2 order-sm-2 order-md-1">
                        <div class="sidebar-style mr-30">
                            <div class="sidebar-widget">
                                <h4 class="pro-sidebar-title">جستجو </h4>
                                <div class="pro-sidebar-search mb-50 mt-25">
                                    <form class="pro-sidebar-search-form">
                                        <input id="searchProduct" type="text" placeholder="... جستجو "
                                          value="{{(request()->has('search')) ? request()->search : ''}}"
                                        >
                                        <button onclick="filter()"  type="button">
                                            <i class="sli sli-magnifier"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="sidebar-widget">
                                <h4 class="pro-sidebar-title"> دسته بندی </h4>
                                <div class="sidebar-widget-list mt-30">
                                    <ul>
                                        {{$category->parent->name}}
                                        @foreach($category->parent->children as $children)
                                            <li>
                                                <a style="{{$children->slug == $category->slug ? 'color: #ff3535' : '' }}"
                                                   href="{{ route('home.categories.show',['category'=>$children->slug] ) }}">
                                                    {{$children->name}}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <hr>

                            @foreach($attributes as $attribute)
                                <div class="sidebar-widget mt-30">
                                    <h4 class="pro-sidebar-title">{{$attribute->name}} </h4>
                                    <div class="sidebar-widget-list mt-20">
                                        <ul>
                                            @foreach($attribute->attributeValues as $value)
                                                <li>
                                                    <div class="sidebar-widget-list-left">
                                                        <input type="checkbox" class="attribute-{{$attribute->id}}" value="{{$value->value}}"
                                                               onchange="filter()"
                                                               {{ (request()->has('attribute.'.$attribute->id) && in_array($value->value , explode('-',request()->attribute[$attribute->id]  ) ) ) ? 'checked' : '' }}
                                                        > <a href="#">{{$value->value}} </a>
                                                        <span class="checkmark"></span>
                                                    </div>
                                                </li>
                                            @endforeach

                                        </ul>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                            <div class="sidebar-widget mt-30">
                                <h4 class="pro-sidebar-title">{{$variation->name}}</h4>
                                <div class="sidebar-widget-list mt-20">
                                    <ul>
                                        @foreach($variation->variationValues as $variationValue)
                                            <li>
                                                <div class="sidebar-widget-list-left">
                                                    <input class="variation" type="checkbox" value="{{$variationValue->value}}" onchange="filter()"
                                                    {{ ( request()->has('variation') && in_array($variationValue->value,explode('-',request('variation')) ) ) ? 'checked' : ''}}
                                                    >
                                                    <a href="#">{{$variationValue->value}} </a>
                                                    <span class="checkmark"></span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form id="filter-form">

                    @foreach($attributes as $attribute)

                        <input id="attribute-input-{{$attribute->id}}" type="hidden" name="attribute[{{$attribute->id}}]" >

                    @endforeach

                    <input id="variation-input" type="hidden" name="variation" >

                    <input id="sort-by-input" type="hidden" name="sortBy" >

                    <input id="search-input" type="hidden" name="search" >

                    </form>
                    <!-- content -->
                    <div class="col-lg-9 order-1 order-sm-1 order-md-2">
                        <!-- shop-top-bar -->
                        <div class="shop-top-bar" style="direction: rtl;">

                            <div class="select-shoing-wrap">
                                <div class="shop-select">
                                    <select onchange="filter()" id="sort-by-select">
                                        <option value="default"> مرتب سازی </option>
                                        <option value="maxPrice"
                                            {{(request()->has('sortBy') && request()->sortBy == 'maxPrice') ? 'selected' : ''}}
                                        > بیشترین قیمت </option>
                                        <option value="minPrice"
                                            {{(request()->has('sortBy') && request()->sortBy == 'minPrice') ? 'selected' : ''}}
                                        > کم ترین قیمت </option>
                                        <option value="latest"
                                            {{(request()->has('sortBy') && request()->sortBy == 'latest') ? 'selected' : ''}}
                                        > جدیدترین </option>
                                        <option value="oldest"
                                            {{(request()->has('sortBy') && request()->sortBy == 'oldest') ? 'selected' : ''}}
                                        > قدیمی ترین </option>
                                    </select>
                                </div>

                            </div>

                        </div>

                        <div class="shop-bottom-area mt-35">
                            <div class="tab-content jump">

                                <div class="row ht-products" style="direction: rtl;">
                                    @foreach($products as $product)
                                        <div class="col-xl-4 col-md-6 col-lg-6 col-sm-6">
                                            <!--Product Start-->
                                            <div class="ht-product ht-product-action-on-hover ht-product-category-right-bottom mb-30">
                                                <div class="ht-product-inner">
                                                    <div class="ht-product-image-wrap">
                                                        <a href="{{route('home.products.show',['product'=>$product->slug])}}" class="ht-product-image">
                                                            <img src=" {{ url ( env( 'PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH' ).$product->primary_image) }} " alt="{{$product->primary_image}}"/>
                                                        </a>
                                                        <div class="ht-product-action">
                                                            <ul>
                                                                <li>
                                                                    <a href="#" data-toggle="modal"
                                                                       data-target="#exampleModal-{{$product->id}}"><i
                                                                            class="sli sli-magnifier"></i><span
                                                                            class="ht-product-action-tooltip"> مشاهده سریع
                                                    </span></a>
                                                                </li>
                                                                <li>
                                                                    @auth()
                                                                        @if($product->wishlist(auth()->id()))

                                                                            <a href="{{route('home.wishlist.remove',['product'=>$product->id])}}"><i class="fas fa-heart" style="color: red"></i>

                                                                            </a>
                                                                        @else
                                                                            <a href="{{route('home.wishlist.add', ['product'=> $product->id])}}"><i class="sli sli-heart"></i>

                                                                            </a>
                                                                        @endif
                                                                    @else
                                                                        <a href="{{route('home.wishlist.add',['product' => $product->id])}}"><i class="sli sli-heart"></i>

                                                                        </a>
                                                                    @endauth
                                                                </li>
                                                                <li>
                                                                    <a href="#"><i class="sli sli-refresh"></i><span
                                                                            class="ht-product-action-tooltip"> مقایسه
                                                    </span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="#"><i class="sli sli-bag"></i><span
                                                                            class="ht-product-action-tooltip"> افزودن به سبد
                                                        خرید </span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="ht-product-content">
                                                        <div class="ht-product-content-inner">
                                                            <div class="ht-product-categories">
                                                                <a href="#">{{$product->category->name}}</a>
                                                            </div>
                                                            <h4 class="ht-product-title text-right">
                                                                <a href="{{route('home.categories.show',['category'=>$product->slug])}}"> {{$product->name}} </a>
                                                            </h4>
                                                            <div class="ht-product-price">
                                                                @if($product->quantity_check)
                                                                    @if(@$product->sale_check)
                                                                        <span class="new">
                                                {{number_format($product->sale_check->sale_price)}}
                                                تومان
                                                    </span>
                                                                        <span class="old">
                                                {{number_format($product->min_price->price)}}
                                                    تومان
                                                    </span>
                                                                    @else
                                                                        <span class="new">
                                                {{number_format($product->min_price->price)}}
                                                    تومان
                                                    </span>
                                                                    @endif
                                                                @else
                                                                    <div class="not-in-stock">
                                                                        <p class="text-white">ناموجود</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="ht-product-ratting-wrap">
                                                                <div id="dataReadonlyReview"
                                                                     data-rating-stars="5"
                                                                     data-rating-readonly="true"
                                                                     data-rating-value="{{$product->rates->avg('rate')}}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <!--Product End-->
                                        </div>
                                    @endforeach
                                </div>

                            </div>

                            <div id="pagination" class="pro-pagination-style text-center mt-30">
                               {{$products->withQueryString()->links()}}
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>



    <!-- Modal -->
    @foreach($products as $product)
        <div class="modal fade" id="exampleModal-{{$product->id}}" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-7 col-sm-12 col-xs-12" style="direction: rtl;">
                                <div class="product-details-content quickview-content">
                                    <h2 class="text-right mb-4">{{$product->name}}</h2>
                                    <div class="product-details-price variation-div">
                                        @if($product->quantity_check)
                                            @if($product->sale_check)
                                                <span class="new">
                                                {{number_format($product->sale_check->sale_price)}}
                                                تومان
                                                </span>
                                                <span class="old">
                                                {{number_format($product->min_price->price)}}
                                                    تومان
                                                </span>
                                            @else
                                                <span class="new">
                                                {{number_format($product->min_price->price)}}
                                                    تومان
                                                </span>
                                            @endif
                                        @else
                                            <div class="not-in-stock">
                                                <p class="text-white">ناموجود</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="pro-details-rating-wrap">
                                        <div id="dataReadonlyReview"
                                             data-rating-stars="5"
                                             data-rating-readonly="true"
                                             data-rating-value="{{$product->rates->avg('rate')}}">
                                        </div>
                                        <span class="mx-2">|</span>
                                        <span>3 دیدگاه</span>
                                    </div>
                                    <p class="text-right">
                                        {{$product->description}}
                                    </p>
                                    <div class="pro-details-list text-right">
                                        <ul class="text-right">
                                            @foreach($product->attributes as $attributes)
                                                <li>-
                                                    {{$attributes->attribute->name}}
                                                    :{{$attributes->value}}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @php
                                        if ($product->sale_check)
                                            {
                                                $variationProductId =$product->sale_check;
                                            }else
                                            {
                                                $variationProductId =$product->min_price;
                                            }
                                    @endphp
                                    <div class="pro-details-size-color text-right">
                                        <div class="pro-details-size w-50">
                                            <span>{{$product->variations->first()->attribute->name}}</span>
                                            <select class="form-control variation-select">
                                                @foreach($product->variations as $variation)
                                                    <option value="{{json_encode($variation->only(['quantity','id','sale_price','is_sale','price']))}}"
                                                        {{ $variationProductId->id == $variation->id ? 'selected' : ''}}
                                                    > {{$variation->value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="pro-details-quality">
                                        <div class="cart-plus-minus">
                                            <input class="cart-plus-minus-box quantity-input" type="text" name="qtybutton" value="1" data-max="5"/>
                                        </div>
                                        <div class="pro-details-cart">
                                            <a href="#">افزودن به سبد خرید</a>
                                        </div>
                                        <div class="pro-details-wishlist">
                                            @auth()
                                                @if($product->wishlist(auth()->id()))
                                                    <a href="{{route('home.wishlist.remove',['product'=>$product->id])}}"><i class="fas fa-heart" style="color: red"></i>

                                                    </a>
                                                @else
                                                    <a href="{{route('home.wishlist.add', ['product'=> $product->id])}}"><i class="sli sli-heart"></i>

                                                    </a>
                                                @endif
                                            @else
                                                <a href="{{route('home.wishlist.add',['product' => $product->id])}}"><i class="sli sli-heart"></i>

                                                </a>
                                            @endauth
                                        </div>
                                        <div class="pro-details-compare">
                                            <a title="Add To Compare" href="#"><i class="sli sli-refresh"></i></a>
                                        </div>
                                    </div>
                                    <div class="pro-details-meta">
                                        <span>دسته بندی :</span>
                                        <ul>
                                            <li>
                                                <a href="#">
                                                    {{$product->category->parent->name}} , {{$product->category->name}}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="pro-details-meta">
                                        <span>تگ ها :</span>
                                        <ul>
                                            @foreach($product->tags as $tag)
                                                <li><a href="#"> {{$tag->name}}</a></li>
                                            @endforeach

                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5 col-sm-12 col-xs-12">
                                <div class="tab-content quickview-big-img">

                                    <div id="pro-primary-{{$product->id}}" class="tab-pane fade show active">
                                        <img src="{{url(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH').$product->primary_image)}}" alt="{{$product->primary_image}}" />
                                    </div>
                                    @foreach($product->images as $image)
                                        <div id="pro-{{$image->id}}" class="tab-pane fade">
                                            <img src="{{url(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH').$image->image)}}" alt="{{$image->image}}" />
                                        </div>
                                    @endforeach

                                </div>
                                <!-- Thumbnail Large Image End -->
                                <!-- Thumbnail Image End -->
                                <div class="quickview-wrap mt-15">
                                    <div class="quickview-slide-active owl-carousel nav nav-style-2" role="tablist">
                                        <a class="active" data-toggle="tab" href="#pro-primary-{{$product->id}}">
                                            <img src="{{url(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH').$product->primary_image)}}" alt="{{$product->primary_image}}" />
                                        </a>
                                        @foreach($product->images as $image)
                                        <a data-toggle="tab" href="#pro-{{$image->id}}">
                                            <img src="{{url(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH').$image->image)}}" alt="{{$image->image}}" />
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- Modal end -->
@endsection
