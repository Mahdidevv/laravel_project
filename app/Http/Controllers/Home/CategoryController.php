<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request ,Category $category)
    {
        $attributes = $category->attributes()->where('is_filter',1)->with('attributeValues')->get();
        $variation = $category->attributes()->where('is_variation',1)->with('variationValues')->first();
        $categoryParent = $category->parent()->first();
        $categoryChildren = $categoryParent->children;
        $products = $category->products()->filter()->search()->paginate(10);
        return view('home.categories.show',compact('category','attributes','variation','categoryParent','categoryChildren','products'));
    }
}
