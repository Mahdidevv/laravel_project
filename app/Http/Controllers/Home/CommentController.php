<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use App\Models\ProductRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request, Product $product)
    {

        $validator = Validator::make($request->all(), [
            'text' => 'required',
            'rate' => 'required|digits_between:0,5'
        ]);

        if ($validator->fails()) {
            return redirect()->to(url()->previous() . '#comments')->withErrors($validator);
        } else {
            if (auth()->check()) {
                try {
                    DB::beginTransaction();
                    $comment = Comment::create([
                        'user_id' => auth()->id(),
                        'product_id' => $product->id,
                        'text' => $request->text
                    ]);
                    $productRate = ProductRate::create([
                        'user_id' => auth()->id(),
                        'product_id' => $product->id,
                        'rate' => $request->rate
                    ]);

                    DB::commit();

                } catch (\Exception $ex) {
                    DB::rollBack();
                    alert()->error('شکست', 'مشکلی در ثبت نظر به وجود آمده');
                }
                alert()->success('باتشکر', 'نظر شما با موفقیت ثبت شد');
                return redirect()->back();
            } else {
                return redirect()->route('login');
            }

        }
    }
}
