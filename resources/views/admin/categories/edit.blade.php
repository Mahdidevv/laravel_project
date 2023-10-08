@extends('admin.layouts.admin')
@section('title')
    create attributes
@endsection

@section('script')
    <script>
        $('#attributeSelect').selectpicker({
            'title':'انتخاب ویژگی'
        });


        $('#attributeSelect').on('changed.bs.select',function (){
            let attributesSelected = $(this).val();
            let attributes = @json($attributes);
            let attributeForFilter = [];

            attributes.map((attribute)=>{
                $.each(attributesSelected,function (i,element){
                    if(attribute.id == element){
                        attributeForFilter.push(attribute);
                    }
                })
            })
            $("#attributeIsFilterSelect").find("option").remove();
            $("#variationSelect").find("option").remove();
            attributeForFilter.forEach((element)=>{
                let attributeFilterOption = $("<option/>",{
                    value : element.id,
                    text : element.name
                });

                let attributeVariation = $("<option/>",{
                    value : element.id,
                    text : element.name
                });


                $("#attributeIsFilterSelect").append(attributeFilterOption);
                $("#attributeIsFilterSelect").selectpicker('refresh');

                $("#variationSelect").append(attributeVariation);
                $("#variationSelect").selectpicker('refresh');
            })
        });

        $('#attributeIsFilterSelect').selectpicker({
            'title':'انتخاب ویژگی'
        });

        $('#variationSelect').selectpicker({
            'title':'انتخاب ویژگی'
        });
    </script>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 bg-white p-3">
            <div class="mb-4">
                <h5 class="font-weight-bold">
                    ویرایش دسته بندی  {{ $category->name }}
                </h5>
                <hr>
                @include('admin.sections.errors')
                <form action="{{route('admin.categories.update',['category'=>$category->id])}}" method="post">
                    @csrf
                    @method('put')
                    <div class="form row">
                        <div class="form-group col-md-3">
                            <label for="name">نام</label>
                            <input class="form-control" name="name" id="name" type="text" value="{{$category->name}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="slug">نام انگلیسی</label>
                            <input class="form-control" name="slug" id="slug" type="text" value="{{$category->slug}}">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="parent_id">والد</label>
                            <select class="form-control" name="parent_id" id="parent_id">
                                <option value="0">بدون والد</option>
                                @foreach($parentCategories as $parentCategory)
                                    {{$parentCategory->id}}
                                    <option value="{{$parentCategory->id}}" {{$category->parent_id == $parentCategory->id ? 'selected' : ''}}>
                                        {{$parentCategory->name}}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="is_active">وضعیت</label>
                            <select class="form-control" name="is_active" id="is_active">

                                <option value="1"{{$category->getRawOriginal('is_active') ? 'selected' : ''}} > فعال</option>
                                <option value="0" {{$category->getRawOriginal('is_active') ? '' : 'selected'}} >غیر فعال</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="attribute_ids">ویژگی</label>
                            <select class="form-control" name="attribute_ids[]" id="attributeSelect" multiple data-live-search="true" >
                                @foreach($attributes as $attribute)
                                    <option value="{{$attribute->id}}"
                                        {{in_array($attribute->id,$category->attributes()->pluck('id')->toarray()) ? 'selected' : ''}}
                                    >
                                        {{$attribute->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="attribute_is_filter_ids">انتخاب ویژگی قابل فیلتر</label>
                            <select class="form-control" name="attribute_is_filter_ids[]" id="attributeIsFilterSelect" multiple data-live-search="true" >
                                @foreach($category->attributes()->wherePivot('is_filter',1)->get() as $attribute)
                                    <option value="{{ $attribute->id }}" selected> {{$attribute->name}} </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="variation_id">انتخاب ویژگی متغییر</label>
                            <select class="form-control" name="variation_id" id="variationSelect" data-live-search="true" >
                                <option
                                   value="{{$category->attributes()->wherePivot('is_variation',1)->first()->id}}" selected>
                                    {{$category->attributes()->wherePivot('is_variation',1)->first()->name}}
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="icon">آیکون</label>
                            <input class="form-control" name="icon" id="icon" type="text" value="{{$category->icon}}">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="description">توضیحات</label>
                            <textarea class="form-control" name="description" id="description">{{$category->description}}</textarea>
                        </div>
                    </div>
                    <button class="btn btn-outline-dark" type="submit" >ثبت</button>
                    <a href="{{route('admin.categories.index')}}" class="btn btn-dark">بازگشت</a>
                </form>
            </div>
        </div>
    </div>
@endsection
