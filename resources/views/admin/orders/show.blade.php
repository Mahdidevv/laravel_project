@extends('admin.layouts.admin')
@section('title')
    show order
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="mb-4">
                <h5 class="font-weight-bold">
                     {{$order->name}}
                </h5>
                <hr>
                @include('admin.sections.errors')
                <form action="{{route('admin.orders.store')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>نام کاربر</label>
                            <input class="form-control" value=" {{($order->user->name == null) ? 'کاربر' : $order->user->name}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>کد کوپن</label>
                            <input class="form-control" value=" {{($order->coupon_id == null) ? 'بدون کوپن' : $order->coupon->name}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>وضعیت</label>
                            <input class="form-control" value=" {{$order->status}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>مبلغ</label>
                            <input class="form-control" value=" {{number_format($order->total_amount)}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>هزینه ارسال</label>
                            <input class="form-control" value=" {{number_format($order->delivery_amount)}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>مبلغ کد تخفیف</label>
                            <input class="form-control" value=" {{number_format($order->coupon_amount)}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>مبلغ پرداختی</label>
                            <input class="form-control" value=" {{number_format($order->paying_amount)}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>نوع پرداخت</label>
                            <input class="form-control" value=" {{$order->payment_type}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>وضعیت پرداخت</label>
                            <input class="form-control" value=" {{$order->payment_status}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>زمان ثبت</label>
                            <input class="form-control" value=" {{verta($order->create_at)}} " disabled>
                        </div>
                        <div class="form-group col-md-12">
                            <label>آدرس</label>
                            <textarea class="form-control" disabled>{{ $order->address->address}}</textarea>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <h5>سفارشات</h5>
                            <table class="table table-bordered table-striped text-center">
                                <thead>
                                <tr>
                                    <th> تصویر محصول </th>
                                    <th> نام محصول </th>
                                    <th> فی </th>
                                    <th> تعداد </th>
                                    <th> قیمت کل </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->orderItems as  $orderItem)
                                    <tr>
                                        <td class="product-thumbnail">
                                            <a href="{{route('admin.products.show',['product'=>$orderItem->product->slug])}}">
                                                <img width="70" src="{{url(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH').$orderItem->product->primary_image)}}" alt="">
                                            </a>
                                        </td>
                                        <td class="product-name">
                                            <a href="{{route('admin.products.show',['product'=>$orderItem->product->slug])}}"> {{$orderItem->product->name}} </a>
                                        </td>
                                        <td class="product-price-cart"><span class="amount">
                                                        {{number_format($orderItem->price)}}
                                                        تومان
                                                    </span></td>
                                        <td class="product-quantity">
                                            {{ $orderItem->quantity}}
                                        </td>
                                        <td class="product-subtotal">
                                            {{number_format($orderItem->subtotal)}}
                                            تومان
                                        </td>
                                    </tr>
                                @endforeach


                                </tbody>
                            </table>
                        </div>
                    </div>
                    <a href="{{route('admin.orders.index')}}" class="btn btn-dark">بازگشت</a>
                </form>
            </div>
        </div>
    </div>
@endsection
