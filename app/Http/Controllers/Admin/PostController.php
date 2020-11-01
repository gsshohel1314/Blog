<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Post;
use App\Category;
use App\Tag;
use App\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AuthorPostApprove;
use App\Notifications\NewPostNotify;
use Carbon\Carbon;
use Session;
use Storage;
use Auth;


class PostController extends Controller
{
    public function index()
    {
        $posts=Post::latest()->get();
        return view('admin.post.index',compact('posts'));
    }

    public function create()
    {
        $categories=Category::all();
        $tags=Tag::all();
        return view('admin.post.create',compact('categories','tags'));
    }

    public function store(Request $request)
    {
        // return $request->all();
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
        $post->is_approved = true;
        $post->save();

        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);

        // notification
        $subscribers=Subscriber::all();
        foreach ($subscribers as $subscriber) {
            Notification::route('mail', $subscriber->email)->notify(new NewPostNotify($post));
        }
        // notification end

        Session::flash('success', 'Post Successfully Saved :)');
        return redirect()->Route('admin.post.index');
    }

    public function show(Post $post)
    {
        return view('admin.post.show',compact('post'));
    }

    public function edit(Post $post)
    {
        $categories=Category::all();
        $tags=Tag::all();
        return view('admin.post.edit',compact('categories','tags','post'));
    }

    public function update(Request $request, Post $post)
    {
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
        $post->is_approved = true;
        $post->save();

        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);

        Session::flash('success', 'Post Successfully Updated :)');
        return redirect()->Route('admin.post.index');
    }

    public function destroy(Post $post)
    {
        if(Storage::disk('public')->exists('post/'.$post->image)){
            Storage::disk('public')->delete('post/'.$post->image);
        }

        $post->categories()->detach();
        $post->tags()->detach();
        $post->delete();

        Session::flash('success', 'Post Successfully Deleted :)');
        return redirect()->back();
    }

    public function pending(){
        $posts=Post::where('is_approved',false)->get();
        return view('admin.post.pending',compact('posts'));
    }

    public function approve($id){
        $post=Post::find($id);

        if($post->is_approved == false){
            $post->is_approved = true;
            $post->save();

            // notification
            $post->user->notify(new AuthorPostApprove($post));
            // notification end

            // notification
            $subscribers=Subscriber::all();
            foreach ($subscribers as $subscriber) {
                Notification::route('mail', $subscriber->email)->notify(new NewPostNotify($post));
            }
            // notification end

            Session::flash('success', 'Post Successfully Approved :)');
        }else{
            Session::flash('info', 'This Post Is Already Approved :)');
        }
        return redirect()->back();
    }
}
