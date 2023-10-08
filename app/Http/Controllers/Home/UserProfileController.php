<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function index()
    {
        return view('home.users_profile.index');
    }

    public function comments()
    {
        $comments = Comment::where('user_id',auth()->id())->where('approved',1)->with(['user','product'])->get();
        return view('home.users_profile.comments',compact('comments'));
    }

    public function wishlist()
    {
        $wishlist = Wishlist::where('user_id',auth()->id())->with('product')->get();
        return view('home.users_profile.wishlist',compact('wishlist'));
    }
}
