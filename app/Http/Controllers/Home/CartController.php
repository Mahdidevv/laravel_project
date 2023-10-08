<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Province;
use Carbon\Carbon;
use Darryldecode\Cart\Cart;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;

class CartController extends Controller
{
    public function index()
    {
        return view('home.cart.index');
    }
     public function checkCoupon(Request $request)
    {
        if (auth()->check())
        {
            $coupon =Coupon::where('code',$request->code)->where('expired_at','>',Carbon::now())->first();
            if ($coupon == null)
            {
                alert()->warning('توجه','این کد تخفیف وجود ندارد');
                return redirect()->back();
            }
            if (Order::where('coupon_id',$coupon->id)->where('user_id',auth()->id())->where('payment_status',1)->exists())
            {
                alert()->warning('توجه','این کد قبلا توسط شما استفاده شده است');
                return redirect()->back();
            }
            else
            {
                if ($coupon->getRawOriginal('type') == 'amount')
                {
                    session()->put('coupon',['code' => $request->code,'amount'=>$coupon->amount]);
                }
                else
                {
                    $total = \Cart::getTotal();
                    $percentage = (($total * $coupon->percentage) / 100) > $coupon->max_percentage_amount ? $coupon->max_percentage_amount : ($total * $coupon->percentage) / 100;
                    session()->put('coupon',['code'=>$request->code,'amount'=>$percentage]);
                }
                alert()->success('باتشکر','کد تخفیف اعمال شد');
                return redirect()->back();
            }
        }
        else
        {
            alert()->warning('شکست','شما باید ابتدا وارد حساب کاربری خود شوید');
            return redirect()->route('login');
        }
    }

    public function add(Request $request , $productId)
    {
        $request->validate([
            'qtybutton'=>'required',
        ]);

        $product = Product::findOrFail($productId);
        $productVariation = ProductVariation::findOrFail(json_decode($request->variation)->id);

        if ($request->qtybutton > $productVariation->quantity){
            alert('توجه','این تعداد از این محصول موجود نیست');
            return redirect()->back();
        }
        $rowId = $product->id."-".$productVariation->id;

        foreach (\Cart::getContent() as $item)
        {
            if ($item->id == $rowId)
            {
                $quantity = $item->quantity;
                $quantity += $request->qtybutton;
                if ($quantity > $productVariation->quantity)
                {
                    alert()->success('توجه','این تعداد از این محصول موجود نیست');
                    return redirect()->back();
                }
                else
                {
                    \Cart::update($rowId, array(
                        'quantity' => $quantity
                    ));
                    alert()->success('موفق','تعداد محصول مورد نظر آپدیت شد');
                    return redirect()->back();
                }
            }
        }
        \Cart::add(array(
            'id' => $rowId,
            'name' => $product->name,
            'price' => ( $productVariation->is_sale ) ? $productVariation->sale_price : $productVariation->price ,
            'quantity' => $request->qtybutton,
            'attributes' => $productVariation->toArray(),
            'associatedModel' => $product
        ));

        alert()->success('موفق','محصول به سبد خرید اضافه شد');
        return redirect()->back();

    }

    public function update(Request $request)
    {
        $request->validate([
            'qtybutton'=>'required'
        ]);
        foreach ($request->qtybutton as $rowId => $quantity)
        {
            $item = \Cart::get($rowId);

            if ($quantity > $item->attributes->quantity || $quantity == 0)
            {
                alert()->error('شکست','این تعداد از این محصول موجود نیست');
                return redirect()->back();
            }
            else
            {
                \Cart::update($rowId, array(
                    'quantity' => array(
                        'relative' => false,
                        'value' => $quantity
                    ),
                ));
            }
        }
        alert()->success('موفق','تعداد محصول آپدیت شد');
        return redirect()->back();
    }

    public function remove($rowId)
    {
        \Cart::remove($rowId);
        alert()->warning('موفق','محصول مورد نظر از سبد خرید حذف گردید');
        return redirect()->back();
    }

    public function clear()
    {
        \Cart::clear();
        alert()->warning('موفق','تمامی محصولات از سبد خرید حذف شدند');
        return redirect()->back();
    }

    public function checkout()
    {
        $addresses = Address::where('user_id',auth()->id())->with(['province','city'])->get();
        $provinces = Province::all();
        return view('home.cart.checkout',compact('addresses','provinces'));
    }

    public function ordersUser()
    {
        $orders = Order::where('user_id',auth()->id())->with('orderItems.product')->get();

        return view('home.users_profile.orders',compact('orders'));
    }
}
