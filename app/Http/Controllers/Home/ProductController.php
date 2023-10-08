<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        $attributes = $product->attributes()->with('attribute')->get();
        $variation = $product->variations()->with('attribute')->first();
        $categoryParent = $product->category()->with('parent')->first();
        $approvedComments = $product->comments()->with('user')->get();
        $categoryChildren = $categoryParent->children;
        $productImages = $product->images;
        return view('home.products.show',compact('product','attributes','variation','categoryParent','categoryChildren','productImages','approvedComments'));
    }
}
