<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->paginate(2);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all();
        $tags = Tag::all();
        $categories = Category::where('parent_id', '!=', 0)->get();
        return view('admin.products.create', compact('brands', 'tags', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'brand_id' => 'required',
            'is_active' => 'required',
            'tag_ids' => 'required',
            'description' => 'nullable',
            'category_id' => 'required',
            'attributes_ids' => 'required',
            'attributes_ids.*' => 'required',
            'variation_values' => 'required',
            'variation_values.*.*' => 'required',
            'variation_values.price.*' => 'integer',
            'variation_values.quantity.*' => 'integer',
            'delivery_amount' => 'required|integer',
            'delivery_amount_per_product' => 'nullable|integer',
            'primary_image' => 'required',
            'images' => 'required',
            'images.*' => 'mimes:jpg,jpeg,sav,png',
        ]);

        try {
            DB::beginTransaction();
            //Upload Primary Image
            $productImageController = new ProductImageController();
            $fileNameImages = $productImageController->upload($request->primary_image, $request->images);
            //Create Product
            $product = Product::create([
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'primary_image' => $fileNameImages['fileNamePrimaryImage'],
                'description' => $request->description,
                'is_active' => $request->is_active,
                'delivery_amount' => $request->delivery_amount,
                'delivery_amount_per_product' => $request->delivery_amount_per_product,
            ]);
            //Upload Other Images
            foreach ($fileNameImages['fileNameImages'] as $fileNameImage) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $fileNameImage
                ]);
            }
            //Create ProductAttribute
            $productAttributeController = new ProductAttributeController();
            $productAttributeController->store($product, $request->attributes_ids);
            //Create ProductVariation
            $category = Category::find($request->category_id);
            $attributeId = $category->attributes()->wherePivot('is_variation', 1)->first()->id;
            $productVariationController = new ProductVariationController();
            $productVariationController->store($request->variation_values, $attributeId, $product);
            //Create ProductTags
            $product->tags()->attach($request->tag_ids);

            DB::commit();
        } catch (\Exception $ex) {
            alert()->error('مشکل', $ex->getMessage());
            return redirect()->back();
        }
        alert()->success('با تشکر', 'محصول مورد نظر اضافه شد');
        return redirect()->route('admin.products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $productAttributes = $product->attributes()->with('attribute')->get();
        $productVariations = $product->variations;
        $productTags =$product->tags;
        $productImages = $product->images;
        return view('admin.products.show', compact('product', 'productAttributes', 'productVariations','productTags','productImages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $brands = Brand::all();
        $tags = Tag::all();
        $productTags = $product->tags()->pluck('id')->toArray();
        $productAttributes = $product->attributes()->with('attribute')->get();
        $productVariations = $product->variations;
        return view('admin.products.edit', compact('product', 'tags', 'brands', 'productTags', 'productAttributes', 'productVariations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'brand_id' => 'required|exists:brands,id',
            'is_active' => 'required',
            'tag_ids' => 'required',
            'tag_ids.*' => 'exists:tags,id',
            'description' => 'nullable',
            'attribute_values' => 'required',
            'attribute_values.*' => 'required',
            'variation_values' => 'required',
            'variation_values.*.price' => 'integer',
            'variation_values.*.quantity' => 'integer',
            'variation_values.*.sku' => 'integer',
            'delivery_amount' => 'required|integer',
            'delivery_amount_per_product' => 'nullable|integer',
            'sale_price' => 'nullable|integer',
            'date_on_sale_from' => 'nullable|date',
            'date_on_sale_to' => 'nullable|date',
        ]);
        try {
            DB::beginTransaction();
            //Update Product
            $product->update([
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'description' => $request->description,
                'is_active' => $request->is_active,
                'delivery_amount' => $request->delivery_amount,
                'delivery_amount_per_product' => $request->delivery_amount_per_product,
            ]);
            //Update ProductAttribute
            $productAttributeController = new ProductAttributeController();
            $productAttributeController->update($request->attribute_values);
            //Update ProductVariation
            $productVariationController = new ProductVariationController();
            $productVariationController->update($request->variation_values);
            //Update ProductTags
            $product->tags()->detach();
            $product->tags()->attach($request->tag_ids);

            DB::commit();
        } catch (\Exception $ex) {
            alert()->error('مشکل در ویرایش محصول', $ex->getMessage());
            return redirect()->back();
        }
        alert()->success('با تشکر', 'محصول مورد نظر ویرایش شد');
        return redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function editCategory(Request $request,Product $product)
    {
        $categories = Category::where('parent_id','!=',0)->get();
        return view('admin.products.category-edit',compact('product','categories'));
    }
    public function updateCategory(Request $request,Product $product)
    {
        $request->validate([
            'category_id' => 'required',
            'attributes_ids' => 'required',
            'attributes_ids.*' => 'required',
            'variation_values' => 'required',
            'variation_values.*.*' => 'required',
            'variation_values.price.*' => 'integer',
            'variation_values.quantity.*' => 'integer',
        ]);
        try {
            DB::beginTransaction();
            //Update Product
            $product->update([
                'category_id' => $request->category_id,
            ]);
            //Update ProductAttribute
            $productAttributeController = new ProductAttributeController();
            $productAttributeController->change($product, $request->attributes_ids);
            //Update ProductVariation
            $category = Category::find($request->category_id);
            $attributeId = $category->attributes()->wherePivot('is_variation', 1)->first()->id;
            $productVariationController = new ProductVariationController();
            $productVariationController->change($request->variation_values, $attributeId, $product);
            DB::commit();
        } catch (\Exception $ex) {
            alert()->error('مشکل', $ex->getMessage());
            return redirect()->back();
        }
        alert()->success('با تشکر', 'محصول مورد نظر اضافه شد');
        return redirect()->route('admin.products.index');
    }
}
