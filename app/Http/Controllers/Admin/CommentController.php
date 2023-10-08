<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::latest()->paginate(20);
        return view('admin.comments.index',compact('comments'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return view('admin.comments.show',compact('comment'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        alert()->success('موفق','کامنت مورد نظر حذف شد');
        return redirect()->route('admin.comments.index');
    }

    public function updateApproved(Comment $comment)
    {
        if($comment->getRawOriginal('approved'))
        {
            $comment->update([
                'approved' => 0
            ]);
        }
        else
        {
            $comment->update([
                'approved' => 1
            ]);
        }
        alert()->success('موفق','وضعیت کامنت مورد نظر تغییر کرد');
        return redirect()->route('admin.comments.index');
    }
}
