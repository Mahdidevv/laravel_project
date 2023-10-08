<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function add(Product $product)
    {
        if (auth()->check())
        {
            if (!$product->wishlist(auth()->id()))
            {
                Wishlist::create([
                    'user_id'=>auth()->id(),
                    'product_id' => $product->id
                ]);
                alert()->success('موفق','این محصول با موفقیت به لیست علاقه مندها اضافه شد');
                return redirect()->back();
            }
            else{
                alert()->warning('توجه','قبلا این محصول را به لیست علاقه مندها اضافه کردید');
                return redirect()->back();
            }
        }
        else
        {
            return redirect()->route('login');
        }
    }

    public function remove(Product $product)
    {
        if (auth()->check())
        {
                Wishlist::where('product_id',$product->id)->where('user_id',auth()->id())->delete();
                return  redirect()->back();
        }
        else
        {
            alert()->error('شکست','اشکالی در حذف به وجود اومد');
            return redirect()->route('login');
        }
    }
}
