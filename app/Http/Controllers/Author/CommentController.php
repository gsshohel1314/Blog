<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comment;
use Auth;
use Session;

class CommentController extends Controller
{
    public function index(){
    	$posts = Auth::user()->posts;
    	return view('author.comments', compact('posts'));
    }

    public function destroy($id){
    	$comment = Comment::findOrFail($id);
    	if ($comment->post->user->id == Auth::id()) {
    		$comment->delete();
    		Session::flash('success', 'Comment Successfully Deleted :)');
    	}else{
    		Session::flash('error', 'You are not authorized to delete this comment :)','Access Denied !!!');
    	}
        return redirect()->back();
    }
}
