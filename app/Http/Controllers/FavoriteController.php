<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class FavoriteController extends Controller
{
    public function add($post){
    	$user = Auth::user();
    	$isFavorite = $user->favorite_posts()->where('post_id',$post)->count();

    	if ($isFavorite == 0) {
    		$user->favorite_posts()->attach($post);
    		Session::flash('success', 'Post Successfully Add To Your Favorite List :)');
        	return redirect()->back();
    	}else{
    		$user->favorite_posts()->detach($post);
    		Session::flash('success', 'Post Successfully Removed From Your Favorite List :)');
        	return redirect()->back();
    	}
    }
}
