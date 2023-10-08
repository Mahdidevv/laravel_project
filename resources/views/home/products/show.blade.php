@extends('home.layouts.home')
@section('title')
    صفحه فروشگاه
@endsection

@section('script')
    <script>
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

<div class="product-details-area pt-100 pb-95">
    <div class="container">
        <div class="row">

            <div class="col-lg-6 col-md-6 order-2 order-sm-2 order-md-1" style="direction: rtl;">
                <div class="product-details-content ml-30">
                    <h2 class="text-right"> {{$product->name}} </h2>
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
                        <span> دیدگاه ( {{count($approvedComments)}} )</span>
                    </div>
                    <p class="text-right">
                        {{$product->description}}
                    </p>
                    <div class="pro-details-list text-right">
                        <ul>
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
                            <input class="cart-plus-minus-box" type="text" name="qtybutton" value="2">
                        </div>
                        <div class="pro-details-cart btn-hover">
                            <a href="#"> افزودن به سبد خرید </a>
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

            <div class="col-lg-6 col-md-6 order-1 order-sm-1 order-md-2">
                <div class="product-details-img">
                    <div class="zoompro-border zoompro-span">
                        <img class="zoompro" src="{{url(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH').$product->primary_image)}}"
                             data-zoom-image="{{url(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH').$product->primary_image)}}" alt=""/>
                    </div>
                    <div id="gallery" class="mt-20 product-dec-slider">
                        <a data-image="{{url(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH').$product->primary_image)}}"
                           data-zoom-image="{{url(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH').$product->primary_image)}}">
                            <img width="90" src="{{url(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH').$product->primary_image)}}" alt="">
                        </a>
                        @foreach($productImages as $image)
                            <a data-image="{{url(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH').$image->image)}}"
                               data-zoom-image="{{url(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH').$image->image)}}">
                                <img width="90" src="{{url(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH').$image->image)}}" alt="">
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="description-review-area pb-95">
    <div class="container">
        <div class="row" style="direction: rtl;">
            <div class="col-lg-8 col-md-8">
                <div class="description-review-wrapper">
                    <div class="description-review-topbar nav">
                        <a class="{{ count($errors) > 0  ? '' : 'active' }}" data-toggle="tab" href="#des-details1"> توضیحات </a>
                        <a data-toggle="tab" href="#des-details3"> اطلاعات بیشتر </a>
                        <a class="{{ count($errors) > 0  ? 'active' : '' }}" data-toggle="tab" href="#des-details2">
                            دیدگاه
                            ( {{count($approvedComments)}} )
                        </a>
                    </div>
                    <div class="tab-content description-review-bottom">
                        <div id="des-details1" class="tab-pane {{ (count($errors) > 0 ) ? '' : 'active' }}">
                            <div class="product-description-wrapper">
                                <p class="text-justify">
                                    {{$product->description}}
                                </p>
                                <p class="text-justify">
                                    {{$product->description}}
                                </p>
                            </div>
                        </div>
                        <div id="des-details3" class="tab-pane">
                            <div class="product-anotherinfo-wrapper text-right">
                                <ul>
                                    @foreach($product->attributes as $attributes)
                                        <li><span> {{$attributes->attribute->name}} :</span>
                                            {{$attributes->value}}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div id="des-details2" class="tab-pane {{ (count($errors) > 0 ) ? 'active' : '' }}">

                            <div id="comments" class="review-wrapper">
                                @foreach($approvedComments as $comment)
                                    <div class="single-review">
                                        <div class="review-img">
                                            <img src="{{ ($comment->user->avatar == null) ? url(asset('/images/home/user.png')) : $comment->user->avatar }}" alt="">
                                        </div>
                                        <div class="review-content w-100 text-right">
                                            <p class="text-right">
                                                {{ $comment->text}}
                                            </p>
                                            <div class="review-top-wrap">
                                                <div class="review-name">
                                                    <h4> {{ ($comment->user->name == null) ? 'کاربر گرامی' : $comment->user->name }} </h4>
                                                </div>
                                                <div class="my-2"
                                                     data-rating-stars="5"
                                                     data-rating-value="{{ceil($product->rates->where('product_id',$product->id)->avg('rate'))}}"
                                                     data-rating-readonly="true">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                            <div  class="ratting-form-wrapper text-right">
                                <span> نوشتن دیدگاه </span>

                                <div class="my-2" id="dataReadonlyReview"
                                     data-rating-stars="5"
                                     data-rating-value="0"
                                     data-rating-input="#rateInput">
                                </div>

                                <div class="ratting-form">
                                    <form action="{{route('home.comments.store',['product'=>$product->id])}}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="rating-form-style mb-20">
                                                    <label> متن دیدگاه : </label>
                                                    <div class="my-2">
                                                        @include('home.sections.errors')
                                                    </div>
                                                    <textarea name="text"></textarea>
                                                </div>
                                            </div>
                                            <input type="hidden" id="rateInput" name="rate" value="0">
                                            <div class="col-lg-12">
                                                <div class="form-submit">
                                                    <input type="submit" value="ارسال">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="pro-dec-banner">
                    <a href="#"><img src="{{url('/upload/images/banner/banner-7.png')}}" alt=""></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
