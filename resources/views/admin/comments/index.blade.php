@extends('admin.layouts.admin')
@section('title')
    index comments
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <table class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>#</th>
                    <th>نام کاربر</th>
                    <th>نام محصول</th>
                    <th>متن کامنت</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($comments as $key => $comment)
                    <tr>
                        <th>{{ $comments->firstItem() + $key  }}</th>
                        <th>{{ ($comment->user->name == null) ? $comment->user->cellphone : $comment->user->name }}</th>
                        <th>
                            <a href="{{route('admin.products.show',['product'=>$comment->product->id])}}">
                                {{  $comment->product->name }}
                            </a>
                        </th>
                        <th>{{$comment->text}}</th>
                        <th class=" {{ ($comment->getRawOriginal('approved')) ? 'text-success' : 'text-danger'  }} ">
                            {{$comment->approved}}
                        </th>
                        <th>
                            <div class="d-flex justify-content-center">
                                <a class="btn btn-sm btn-outline-info ml-3"
                                   href="{{route('admin.comments.show',['comment'=>$comment->id])}}"> نمایش </a>
                                <form action="{{route('admin.comments.destroy',['comment'=>$comment->id])}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger mr-3">حذف</button>
                                </form>
                            </div>

                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{$comments->links()}}
        </div>

    </div>

@endsection
