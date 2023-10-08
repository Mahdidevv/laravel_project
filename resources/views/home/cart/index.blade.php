@extends('home.layouts.home');

@section('title')
    صفحه سبد خرید
@endsection

@section('script')
    <script>
            function changeQuantity(tag){
                console.log('mahdi');
               let qunatity = $(tag).val();
               console.log(qunatity);
            };
            {{--$.ajax({--}}
            {{--    type : "POST",--}}
            {{--    url : '{{ url(route('home.cart.update')) }}',--}}
            {{--    data : {'_token': '{{csrf_token()}}','id': id},--}}
            {{--    datatype:"json",--}}
            {{--    success:function (data) {--}}
            {{--        console.log('success');--}}
            {{--    },error:function (data) {--}}
            {{--        console.log('error');--}}
            {{--    }--}}
            {{--});--}}

    </script>
@endsection

@section('content')
    <div class="breadcrumb-area pt-35 pb-35 bg-gray" style="direction: rtl;">
        <div class="container">
            <div class="breadcrumb-content text-center">
                <ul>
                    <li>
                        <a href="{{route('index')}}"> صفحه ای اصلی </a>
                    </li>
                    <li class="active"> سبد خرید </li>
                </ul>
            </div>
        </div>
    </div>

        <div class="cart-main-area pt-95 pb-100 text-right" style="direction: rtl;">
            @if(! \Cart::isEmpty() )
            <div class="container">
                <h3 class="cart-page-title"> سبد خرید شما </h3>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">

                        <form action="{{route('home.cart.update')}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="table-content table-responsive cart-table-content">
                                <table>
                                    <thead>
                                    <tr>
                                        <th> تصویر محصول </th>
                                        <th> نام محصول </th>
                                        <th> فی </th>
                                        <th> تعداد </th>
                                        <th> قیمت </th>
                                        <th> عملیات </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(\Cart::getContent() as $item)
                                        <tr>
                                            <td class="product-thumbnail">
                                                <a href="{{route('home.products.show',['product'=>$item->associatedModel->slug])}}">
                                                    <img width="100" src="{{ url(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH').$item->associatedModel->primary_image)}}" alt="">
                                                </a>
                                            </td>
                                            <td class="product-name">
                                                <a href="{{route('home.products.show',['product'=>$item->associatedModel->slug])}}">
                                                    {{ $item->name}}
                                                    <div style="direction: rtl">
                                                        <p class="mb-0" style="font-size: 12px;color: red">
                                                            {{$item->associatedModel->variations->first()->attribute->name}}
                                                            :
                                                            {{$item->attributes->value}}
                                                        </p>
                                                    </div>
                                                </a>
                                            </td>
                                            <td class="product-price-cart">
                                                <span class="amount">
                                                    {{number_format($item->price)}}
                                                    تومان
                                                </span>
                                                <div style="direction: rtl">
                                                    @if($item->attributes->isSale)
                                                        <p class="mb-0" style="font-size: 12px;color: red">
                                                            تخفیف
                                                            :
                                                            {{$item->attributes->persent_sale}}%
                                                        </p
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="product-quantity">
                                                <div  class="cart-plus-minus">
                                                    <input class="cart-plus-minus-box" type="text" name="qtybutton[{{$item->id}}]" value="{{ $item->quantity}}" data-max="{{ $item->attributes->quantity }}">
                                                </div>
                                            </td>
                                            <td class="product-subtotal">
                                                {{number_format(($item->quantity)*($item->price))}}
                                                تومان
                                            </td>
                                            <td class="product-remove">
                                                <a href="{{route('home.cart.remove',['rowId'=>$item->id])}}">
                                                    <i class="sli sli-close"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="cart-shiping-update-wrapper">
                                        <div class="cart-shiping-update">
                                            <a href="#"> ادامه خرید </a>
                                        </div>
                                        <div class="cart-clear">
                                            <button type="submit"> به روز رسانی سبد خرید </button>
                                            <a href="{{route('home.cart.clear')}}">
                                                پاک کردن سبد خرید
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="row justify-content-between">

                            <div class="col-lg-4 col-md-6">
                                <div class="discount-code-wrapper">
                                    <div class="title-wrap">
                                        <h4 class="cart-bottom-title section-bg-gray"> کد تخفیف </h4>
                                    </div>
                                    <div class="discount-code">
                                        <p> لورم ایپسوم متن ساختگی با تولید سادگی </p>
                                        <form action="{{route('home.coupon.check-coupon')}}" method="POST">
                                            @csrf
                                            <input type="text" required="" name="code">
                                            <button class="cart-btn-2" type="submit"> ثبت </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12">
                                <div class="grand-totall">
                                    <div class="title-wrap">
                                        <h4 class="cart-bottom-title section-bg-gary-cart"> مجموع سفارش </h4>
                                    </div>
                                    <h5>
                                        مبلغ سفارش :
                                        <span>
                                            {{number_format(\Cart::getTotal() + calculatorSalePriceAmount())}}
                                            تومان
                                        </span>
                                    </h5>
                                    @if(calculatorSalePriceAmount() > 0)
                                        <hr>
                                        <h5 style="color: red">
                                            مبلغ تخفیف کالاها :
                                            <span style="color: red">
                                            {{number_format(calculatorSalePriceAmount())}}
                                            تومان
                                            </span>
                                        </h5>
                                    @endif
                                    @if(session()->has('coupon'))
                                        <hr>
                                        <h5 style="color: red">
                                            مبلغ کد تخفیف :
                                            <span style="color: red">
                                            {{number_format(session()->get('coupon.amount'))}}
                                            تومان
                                            </span>
                                        </h5>
                                    @endif
                                    <div class="total-shipping">
                                        <h5>
                                            هزینه ارسال :
                                            @if(!calculatorDeliveryAmount() == 0)
                                                <span>
                                                {{number_format(calculatorDeliveryAmount())}}
                                                تومان
                                                </span>
                                            @else
                                                <span>
                                                رایگان
                                                </span>
                                            @endif
                                        </h5>

                                    </div>
                                    <h4 class="grand-totall-title">
                                        جمع کل:
                                        <span>
                                            {{( number_format(getTotalAmount()))}}
                                            تومان
                                        </span>
                                    </h4>
                                    <a href="./checkout.html"> ادامه فرآیند خرید </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="container cart-empty-content">
                    <div class="row justify-content-center">
                        <div class="col-md-6 text-center">
                            <i class="sli sli-basket"></i>
                            <h2 class="font-weight-bold my-5 mx-5">سبد خرید خالی است.</h2>
                            <p class="mb-40">شما هیچ کالایی در سبد خرید خود ندارید.</p>
                            <a href="{{route('index')}}" > ادامه خرید </a>
                        </div>
                    </div>
                </div>
        </div>
    @endif



@endsection
