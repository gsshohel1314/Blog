<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;
use App\Tag;
use Session;

class PostController extends Controller
{
	public function index(){
		$posts = Post::latest()->approved()->published()->paginate(9);
		return view('allPost',compact('posts'));
	}

    public function details($slug){
    	$post = Post::where('slug',$slug)->approved()->published()->first();

    	$blogKey = 'blog-' . $post->id;

    	if (!Session::has($blogKey)) {
    		$post->increment('view_count');
    		Session::put($blogKey, 1);
    	}

    	$randomPosts = Post::approved()->published()->take(3)->inRandomOrder()->get();

    	return view('post',compact('post','randomPosts'));
    }

    public function postByCategory($slug){
        $category = Category::where('slug',$slug)->first();
        $posts = $category->posts()->approved()->published()->get();
        return view('category_post',compact('category','posts'));
    }

    public function postByTag($slug){
        $tag = Tag::where('slug',$slug)->first();
        $posts = $tag->posts()->approved()->published()->get();
        return view('tag_post',compact('tag','posts'));
    }
}
