@extends('admin.layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="d-flex justify-content-between mb-4">
                <h5 class="font-weight-bold">
                    لیست سفارشات ({{$orders->total()}})
                </h5>
            </div>
            <div>
                <table class="table table-bordered table-striped text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>مبلغ</th>
                        <th>وضعیت</th>
                        <th>نوع پرداخت</th>
                        <th>وضعیت پرداخت</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $key => $order)
                        <tr>
                            <th>{{ $orders->firstItem() + $key}}</th>
                            <th>{{($order->user->name == null) ? 'کاربر' : $order->user->name}}</th>
                            <th>{{number_format($order->paying_amount)}}</th>
                            <th>{{$order->status}}</th>
                            <th>{{$order->payment_type}}</th>
                            <th>{{$order->payment_status}}</th>
                            <th>
                                <a class="btn btn-sm btn-outline-info" href="{{route('admin.orders.show',['order'=>$order->id])}}"> نمایش </a>
                                <a class="btn btn-sm btn-outline-danger" href="{{route('admin.orders.edit',['order'=>$order->id])}}"> ویرایش </a>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{$orders->links()}}
            </div>

        </div>
    </div>
@endsection
