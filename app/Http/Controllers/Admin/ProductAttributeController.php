<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public function store($product,$attributes)
    {
        foreach ($attributes as $key => $value )
        {
            ProductAttribute::create([
                'product_id' => $product->id,
                'attribute_id' => $key,
                'value' => $value,
            ]);
        }
    }

    public function update($attributes)
    {
        foreach ($attributes as $key => $value)
        {
            $productAttribute = ProductAttribute::find($key);
            $productAttribute->update([
                'value' => $value
            ]);
        }
    }

    public function change($product,$attributes)
    {
        ProductAttribute::where('product_id',$product->id)->delete();
        foreach ($attributes as $key => $value )
        {
            ProductAttribute::create([
                'product_id' => $product->id,
                'attribute_id' => $key,
                'value' => $value,
            ]);
        }
    }
}
