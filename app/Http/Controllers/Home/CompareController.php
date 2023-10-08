<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function add(Product $product)
    {
        if (session()->has('compareProduct')) {
            if (in_array($product->id, session()->get('compareProduct'))) {
                alert()->warning('هشدار', 'این محصول قبلا به لیست مقایسه اضافه شده است');
                return redirect()->back();
            } else {
                session()->push('compareProduct', $product->id);
            }
        } else {
            session()->put('compareProduct', [$product->id]);
        }
        alert()->success('موفق', 'محصول با موفقیت به لیست مقایسه اضاف شد');
        return redirect()->back();
    }

    public function index()
    {
        if (session()->has('compareProduct'))
        {
            $products = Product::whereIn('id',session()->get('compareProduct'))->with(['category.parent','variations.attribute','attributes.attribute','rates'])->get();
//            foreach ($products as $product)
//            {
//               var_dump($product->rates);
//            }
            return view('home.compare.index',compact('products'));
        }
        else
        {
            alert()->success('شکست', 'محصول برای نمایش وجود ندارد');
            return redirect()->back();
        }

    }

    public function remove($productId)
    {
        if (session()->has('compareProduct'))
        {
            foreach (session()->get('compareProduct') as $key => $item)
            {
                if ($productId == $item)
                {
                    session()->pull('compareProduct.'.$key);
                }

            }
            if (session()->get('compareProduct') == [] )
            {
                session()->forget('compareProduct');
                return redirect()->route('index');
            }
            return redirect()->route('home.compare.index');
        }
        else
        {
            alert()->error('شکست', 'محصول برای نمایش وجود ندارد');
            return redirect()->route('index');
        }
    }
}
