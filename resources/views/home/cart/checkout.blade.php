@extends('home.layouts.home')
@section('title')
    صفحه سفارش
@endsection
@section('script')
    <script>
        $('#address-input').val($('#address-select').val());
        $('#address-select').change(function() {
            $('#address-input').val($(this).val());
        });
        $('.province-select').change(function () {
            var provinceID = $(this).val();

            if (provinceID) {
                $.ajax({
                    type: "GET",
                    url: "{{ url(route('home.addresses.get-cities-provinces')) }}?province_id=" + provinceID,
                    success: function (res) {
                        if (res) {
                            $(".city-select").empty();

                            $.each(res, function (key, city) {
                                console.log(city);
                                $(".city-select").append('<option value="' + city.id + '">' +
                                    city.name + '</option>');
                            });
                        } else {
                            $(".city-select").empty();
                        }
                    }
                });
            } else {
                $(".city-select").empty();
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
                    <li class="active"> سفارش</li>
                </ul>
            </div>
        </div>
    </div>


    <!-- compare main wrapper start -->
    <div class="checkout-main-area pt-70 pb-70 text-right" style="direction: rtl;">

        <div class="container">
            @if(!session()->has('coupon'))
                <div class="customer-zone mb-20">
                    <p class="cart-page-title">
                        کد تخفیف دارید؟
                        <a class="checkout-click3" href="#"> میتوانید با کلیک در این قسمت کد خود را اعمال کنید </a>
                    </p>
                    <div class="checkout-login-info3">
                        <form action="{{route('home.coupon.check-coupon')}}" method="POST">
                            @csrf
                            <input type="text" required="" name="code">
                            <button class="btn-outline-primary" type="submit"> ثبت </button>
                        </form>
                    </div>
                </div>
            @endif
            <div class="checkout-wrap pt-30">
                <div class="row">

                    <div class="col-lg-7">
                        <div class="billing-info-wrap mr-50">
                            <h3> آدرس تحویل سفارش </h3>

                            <div class="row">
                                <p>
                                    لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان
                                    گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است
                                </p>
                                <div class="col-lg-6 col-md-6">
                                    <div class="billing-info tax-select mb-20">
                                        <label> انتخاب آدرس تحویل سفارش
                                            <abbr class="required" title="required">
                                                *
                                            </abbr>
                                        </label>
                                        <select class="email s-email s-wid" id="address-select">
                                            @foreach($addresses as $address)
                                                <option value="{{$address->id}}"> {{$address->title}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 pt-30">
                                    <button class="collapse-address-create" type="submit"> ایجاد آدرس جدید</button>
                                </div>

                                <div class="col-lg-12">
                                    <div class="collapse-address-create-content"
                                         style="{{ ( count($errors->addressStore) > 0 ) ? 'display:block' : '' }}">

                                        <form action="{{route('home.addresses.store')}}" method="POST">
                                            @csrf
                                            <div class="row">

                                                <div class="tax-select col-lg-6 col-md-6">
                                                    <label>
                                                        عنوان
                                                    </label>
                                                    <input type="text" name="title">
                                                    @error('title','addressStore')
                                                    <p class="input-error-validation">
                                                        <strong>{{$message}}</strong>
                                                    </p>
                                                    @enderror
                                                </div>
                                                <div class="tax-select col-lg-6 col-md-6">
                                                    <label>
                                                        شماره تماس
                                                    </label>
                                                    <input type="text" name="cellphone">
                                                    @error('cellphone','addressStore')
                                                    <p class="input-error-validation">
                                                        <strong>{{$message}}</strong>
                                                    </p>
                                                    @enderror
                                                </div>
                                                <div class="tax-select col-lg-6 col-md-6">
                                                    <label>
                                                        استان
                                                    </label>
                                                    <select class="email s-email s-wid province-select"
                                                            name="province_id">
                                                        @foreach($provinces as $province)
                                                            <option
                                                                value="{{$province->id}}">{{$province->name}}</option>
                                                        @endforeach

                                                    </select>
                                                    @error('province_id','addressStore')
                                                    <p class="input-error-validation">
                                                        <strong>{{$message}}</strong>
                                                    </p>
                                                    @enderror
                                                </div>
                                                <div class="tax-select col-lg-6 col-md-6">
                                                    <label>
                                                        شهر
                                                    </label>
                                                    <select class="email s-email s-wid city-select" name="city_id">

                                                    </select>
                                                    @error('city_id','addressStore')
                                                    <p class="input-error-validation">
                                                        <strong>{{$message}}</strong>
                                                    </p>
                                                    @enderror
                                                </div>
                                                <div class="tax-select col-lg-6 col-md-6">
                                                    <label>
                                                        آدرس
                                                    </label>
                                                    <input type="text" name="address">
                                                    @error('address','addressStore')
                                                    <p class="input-error-validation">
                                                        <strong>{{$message}}</strong>
                                                    </p>
                                                    @enderror
                                                </div>
                                                <div class="tax-select col-lg-6 col-md-6">
                                                    <label>
                                                        کد پستی
                                                    </label>
                                                    <input type="text" name="postal_code">
                                                    @error('postal_code','addressStore')
                                                    <p class="input-error-validation">
                                                        <strong>{{$message}}</strong>
                                                    </p>
                                                    @enderror
                                                </div>

                                                <div class=" col-lg-12 col-md-12">

                                                    <button class="cart-btn-2" type="submit"> ثبت آدرس
                                                    </button>
                                                </div>


                                            </div>

                                        </form>

                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="col-lg-5">
                        <form action="{{route('home.payment')}}" method="POST">
                            @csrf
                            <div class="your-order-area">
                                <h3> سفارش شما </h3>
                                <div class="your-order-wrap gray-bg-4">
                                    <div class="your-order-info-wrap">
                                        <div class="your-order-info">
                                            <ul>
                                                <li> محصول <span> جمع </span></li>
                                            </ul>
                                        </div>
                                        <div class="your-order-middle">
                                            <ul>
                                                @foreach(\Cart::getContent() as $item)
                                                    <li class="d-flex justify-content-between">
                                                        <div>
                                                            {{ $item->name}}
                                                            -
                                                            {{ $item->quantity}}
                                                            <p class="mb-0" style="font-size: 12px;color: red">
                                                                {{$item->associatedModel->variations->first()->attribute->name}}
                                                                :
                                                                {{$item->attributes->value}}
                                                            </p>
                                                        </div>
                                                        <span>
                                                        {{number_format($item->price)}}
                                                        تومان
                                                             @if($item->attributes->isSale)
                                                                <p class="mb-0" style="font-size: 12px;color: red">
                                                            تخفیف
                                                            :
                                                            {{$item->attributes->persent_sale}}%
                                                        </p
                                                            @endif
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="your-order-info order-subtotal">
                                            <ul>
                                                <li> مبلغ
                                                    <span>
                                                        {{number_format(\Cart::getTotal() + calculatorSalePriceAmount())}}
                                                        تومان
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="your-order-info order-shipping">
                                            <ul>
                                                <li> هزینه ارسال
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


                                                    @if(calculatorSalePriceAmount() > 0)
                                                        <hr>
                                                        <h5 style="color: red;font-size: 14px">
                                                            مبلغ تخفیف کالاها :
                                                            <span style="color: red;font-size: 14px">
                                            {{number_format(calculatorSalePriceAmount())}}
                                            تومان
                                            </span>
                                                        </h5>
                                                    @endif
                                                    @if(session()->has('coupon'))
                                                        <hr>
                                                        <h5 style="color: red;font-size: 14px">
                                                            مبلغ کد تخفیف :
                                                            <span style="color: red;font-size: 14px">
                                            {{number_format(session()->get('coupon.amount'))}}
                                            تومان
                                            </span>
                                                        </h5>
                                                    @endif
                                                </li>

                                            </ul>

                                        </div>

                                        <div class="your-order-info order-total">
                                            <ul>
                                                <li>جمع کل
                                                    <span>
                                                        {{( number_format(getTotalAmount()))}}
                                                        تومان </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="payment-method">
                                        <div class="pay-top sin-payment">
                                            <input id="zarinpal" class="input-radio" type="radio" value="zarinpal"
                                                   checked="checked" name="payment_method">
                                            <input type="hidden" id="address-input" name="address_id">
                                            <label for="zarinpal"> درگاه پرداخت زرین پال </label>
                                            <div class="payment-box payment_method_bacs">
                                                <p>
                                                    لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده
                                                    از طراحان گرافیک است.
                                                </p>
                                            </div>
                                        </div>
                                        <div class="pay-top sin-payment">
                                            <input id="pay" class="input-radio" type="radio" value="pay"
                                                   name="payment_method">
                                            <label for="pay">درگاه پرداخت پی</label>
                                            <div class="payment-box payment_method_bacs">
                                                <p>
                                                    لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده
                                                    از طراحان گرافیک است.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="Place-order mt-40">
                                    <button type="submit">ثبت سفارش</button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>

        </div>

    </div>

    <!-- compare main wrapper end -->
@endsection
