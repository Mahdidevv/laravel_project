<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;

class ProductImageController extends Controller
{
    public function upload($primaryImage,$images)
    {
        $fileNamePrimaryImage = $primaryImage->getClientOriginalName();
        $primary = $primaryImage->move(public_path(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH')),generateFileName($fileNamePrimaryImage));
        $fileNameImages = [];
        foreach ($images as $image){
            $fileNameImage = $image->getClientOriginalName();
            $imageName =$image->move(public_path(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH')),generateFileName($fileNameImage));
            array_push($fileNameImages,$imageName->getFileName());
        }
        return ['fileNamePrimaryImage'=>$primary->getFileName(),'fileNameImages'=>$fileNameImages];
    }

    public function edit(Product $product){
        $images = $product->images;
        return view('admin.products.images-edit',compact('product','images'));
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'image_id'=> 'required|exists:product_images,id'
        ]);
        try {
            ProductImage::destroy($request->image_id);
        }catch (Exception $ex)
        {
            alert()->success('شکست',$ex->getMessage());
            return redirect()->back();
        }
        alert()->success('موفق','عکس محصول مورد نظر حذف شد');
        return redirect()->back();
    }

    public function setPrimary(Request $request,Product $product)
    {
        $request->validate([
            'image_id'=> 'required|exists:product_images,id'
        ]);
        try {
            DB::beginTransaction();
            $productImage = ProductImage::find($request->image_id);
            $product->update([
                'primary_image'=>$productImage->image
            ]);
            $this->destroy($request);
            DB::commit();
        }catch (Exception $ex)
        {
            alert()->success('شکست',$ex->getMessage());
            return redirect()->back();
        }

        alert()->success('موفق','عکس محصول مورد به عنوان تصویر اصلی انتخاب شد');
        return redirect()->route('admin.products.index');
    }

    public function add(Request $request,Product $product)
    {
        $request->validate([
            'primary_image'=>'nullable',
            'images'=> 'nullable',
            'images.*'=> 'nullable|mimes:jpeg,jpg,svg'
        ]);
        if ($request->has('primary_image')){
            $fileNamePrimaryImage = $request->primary_image->getClientOriginalName();
            $primary =  $request->primary_image->move(public_path(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH')),generateFileName($fileNamePrimaryImage));

            $product->update([
                'primary_image'=>$primary->getFileName()
            ]);
        }
        if ($request->has('images')){
            foreach ($request->images as $image)
            {
                $fileNameImage = $image->getClientOriginalName();
                $imageName =$image->move(public_path(env('PUBLIC_UPLOAD_PRODUCT_IMAGES_PATH')),generateFileName($fileNameImage));
                ProductImage::create([
                    'image'=> $imageName->getFileName(),
                    'product_id'=>$product->id
                ]);
            }

        }
        alert()->success('موفق','تصویر مورد نظر ویرایش شد');
        return redirect()->back();
    }
}
