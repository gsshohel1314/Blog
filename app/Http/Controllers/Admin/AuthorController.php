<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Session;

class AuthorController extends Controller
{
    public function index(){
    	$authors = User::Authors()
    			->withCount('posts')
    			->withCount('favorite_posts')
    			->withCount('comments')
    			->get();
    	return view('admin.authors',compact('authors'));		
    }

    public function destroy($id){
    	$author = User::findOrFail($id)->delete();

    	Session::flash('success', 'Author Successfully Deleted :)');
        return redirect()->back();
    }
}
