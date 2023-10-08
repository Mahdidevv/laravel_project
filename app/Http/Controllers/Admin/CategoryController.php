<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = Category::where('parent_id',0)->get();
        $attributes = Attribute::all();
        return view('admin.categories.create',compact('parentCategories','attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'parent_id' => 'required',
            'is_active' => 'required',
            'attribute_ids' => 'required',
            'attribute_is_filter_ids' => 'required',
            'variation_id' => 'required'
        ]);
        try {
            DB::beginTransaction();
            $category = Category::create([
                'name' => $request->name,
                'slug' =>$request->slug,
                'parent_id' => $request->parent_id,
                'is_active' => $request->is_active,
                'icon' => $request->icon,
                'description' => $request->description,
            ]);

            foreach ($request->attribute_ids as $attributeId)
            {
                $attribute = Attribute::findOrFail($attributeId);
                $attribute->categories()->attach($category->id,[
                    'is_filter' => in_array($attributeId,$request->attribute_is_filter_ids) ? 1 : 0,
                    'is_variation' => ($request->variation_id == $attributeId) ? 1 : 0
                ]);
            }
            DB::commit();
        }
        catch (\Exception $ex)
        {
            DB::rollBack();
            alert()->error('شکست',$ex->getMessage())->persistent('باشه');
            return redirect()->back();
        }

        alert()->success('موفقیت','دسته بندی با موفقیت اضافه شد');
        return redirect()->route('admin.categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $categoriesIsVariation = $category->attributes()->wherePivot('is_variation',1)->first();
        $categoriesIsFilter = $category->attributes()->wherePivot('is_filter',1)->get();
        return view('admin.categories.show',compact('category','categoriesIsFilter','categoriesIsVariation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $attributes = Attribute::all();
        $categoriesIsVariation = $category->attributes()->wherePivot('is_variation',1)->first();
        $categoriesIsFilter = $category->attributes()->wherePivot('is_filter',1)->get();
        $parentCategories = $category->where('parent_id',0)->get();
        $attributesSelected = $category->attributes()->pluck('id');
        return view('admin.categories.edit',compact('category','attributes','categoriesIsFilter','categoriesIsVariation','parentCategories','attributesSelected'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id,
            'parent_id' => 'required',
            'is_active' => 'required',
            'attribute_ids' => 'required',
            'attribute_is_filter_ids' => 'required',
            'variation_id' => 'required'
        ]);
        try {
            DB::beginTransaction();
            $category->update([
                'name' => $request->name,
                'slug' =>$request->slug,
                'parent_id' => $request->parent_id,
                'is_active' => $request->is_active,
                'icon' => $request->icon,
                'description' => $request->description,
            ]);
            $category->attributes()->detach();
            foreach ($request->attribute_ids as $attributeId)
            {
                $attribute = Attribute::findOrFail($attributeId);
                $attribute->categories()->attach($category->id,[
                    'is_filter' => in_array($attributeId,$request->attribute_is_filter_ids) ? 1 : 0,
                    'is_variation' => ($request->variation_id == $attributeId) ? 1 : 0
                ]);
            }
            DB::commit();
        }
        catch (\Exception $ex)
        {
            DB::rollBack();
            alert()->error('شکست',$ex->getMessage())->persistent('باشه');
            return redirect()->back();
        }

        alert()->success('موفقیت','دسته بندی با موفقیت ویرایش شد');
        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getCategoryAttributes(Category $categoryId)
    {
        $attributes = $categoryId->attributes()->wherePivot('is_variation',0)->get();
        $variation = $categoryId->attributes()->wherePivot('is_variation',1)->first();
        return ['attributes' => $attributes , 'variation' => $variation];
    }
}
