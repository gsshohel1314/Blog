<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Post;
use App\Category;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewAuthorPost;
use Carbon\Carbon;
use Session;
use Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts=Auth::User()->posts()->latest()->get();
        return view('author.post.index',compact('posts'));
    }

    public function create()
    {
        $categories=Category::all();
        $tags=Tag::all();
        return view('author.post.create',compact('categories','tags'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'title'=>'required',
            'image'=>'required',
            'categories'=>'required',
            'tags'=>'required',
            'body'=>'required',
        ]);

        if($request->hasFile('image')){
            $image=$request->file('image');
            $slug=Str::slug($request->title);
            $imageName=$slug.'-'.Carbon::today()->toDateString().'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('post')){
                Storage::disk('public')->makeDirectory('post');
            }
            Image::make($image)->resize(1600,1066)->save(public_path('storage/post/'.$imageName));
        }else{
            $imageName='default.png';
        }

        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->slug = $slug;
        $post->image = $imageName;
        $post->body = $request->body;
        if(isset($request->status)){
            $post->status = true;
        }else{
            $post->status = false;
        }
        $post->is_approved = false;
        $post->save();

        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);

        // notification
        $users=User::where('role_id' , '1')->get();
        Notification::send($users, new NewAuthorPost($post));
        // notification end

        Session::flash('success', 'Post Successfully Saved :)');
        return redirect()->Route('author.post.index');
    }

    public function show(Post $post)
    {
        if($post->user_id != Auth::id()){
            Session::flash('error', 'You are not authorized to access this post !!');
            return redirect()->back();
        }
        return view('author.post.show',compact('post'));
    }

    public function edit(Post $post)
    {
        if($post->user_id != Auth::id()){
            Session::flash('error', 'You are not authorized to access this post !!');
            return redirect()->back();
        }

        $categories=Category::all();
        $tags=Tag::all();
        return view('author.post.edit',compact('categories','tags','post'));
    }

    public function update(Request $request, Post $post)
    {
        if($post->user_id != Auth::id()){
            Session::flash('error', 'You are not authorized to access this post !!');
            return redirect()->back();
        }

        $this->validate($request,[
            'title'=>'required',
            'image'=>'image',
            'categories'=>'required',
            'tags'=>'required',
            'body'=>'required',
        ]);

        if($request->hasFile('image')){
            $image=$request->file('image');
            $slug=Str::slug($request->title);
            $imageName=$slug.'-'.Carbon::today()->toDateString().'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if(Storage::disk('public')->exists('post/'.$post->image)){
                Storage::disk('public')->delete('post/'.$post->image);
            }
            Image::make($image)->resize(1600,1066)->save(public_path('storage/post/'.$imageName));
        }else{
            $imageName=$post->image;
        }

        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->image = $imageName;
        $post->body = $request->body;
        if(isset($request->status)){
            $post->status = true;
        }else{
            $post->status = false;
        }
        $post->is_approved = false;
        $post->save();

        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);

        Session::flash('success', 'Post Successfully Updated :)');
        return redirect()->Route('author.post.index');
    }

    public function destroy(Post $post)
    {
        if($post->user_id != Auth::id()){
            Session::flash('error', 'You are not authorized to access this post !!');
            return redirect()->back();
        }
        
        if(Storage::disk('public')->exists('post/'.$post->image)){
            Storage::disk('public')->delete('post/'.$post->image);
        }

        $post->categories()->detach();
        $post->tags()->detach();
        $post->delete();

        Session::flash('success', 'Post Successfully Deleted :)');
        return redirect()->back();
    }
}
