@extends('admin.layouts.admin')
@section('title')
    show comment
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="mb-4">
                @include('admin.sections.errors')
                <form action="#">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>نام کاربر</label>
                            <input class="form-control"
                                   value=" {{ ($comment->user->name == null) ? $comment->user->cellphone : $comment->user->name }} "
                                   disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>نام محصول</label>
                            <input class="form-control" value=" {{$comment->product->name}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>وضعیت</label>
                            <input class="form-control" value=" {{$comment->approved}} " disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>زمان ثبت</label>
                            <input class="form-control" value=" {{verta($comment->create_at)}} " disabled>
                        </div>
                        <div class="form-group col-md-12">
                            <label>متن کامنت</label>
                            <textarea class="form-control" value=" {{$comment->name}} "
                                      disabled>{{$comment->text}}</textarea>
                        </div>
                    </div>
                    <a href="{{route('admin.comments.index')}}" class="btn btn-dark">بازگشت</a>
                    @if($comment->getRawOriginal('approved'))
                        <a href="{{route('admin.comment.update-approved',['comment'=> $comment->id])}}" class="btn btn-danger">عدم تایید</a>
                    @else
                        <a href="{{route('admin.comment.update-approved',['comment'=> $comment->id])}}" class="btn btn-success">تایید</a>
                    @endif


                </form>
            </div>
        </div>
    </div>
@endsection
