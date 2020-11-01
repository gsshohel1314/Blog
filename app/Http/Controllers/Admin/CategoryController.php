<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Category;
use Carbon\Carbon;
use Session;
use Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories=Category::latest()->get();
        return view('admin.category.index',compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|unique:categories',
            'image' => 'required|mimes:jpeg,bmp,png,jpg',
        ]);

        if($request->hasFile('image')){
            $image=$request->file('image');
            $slug=Str::slug($request->name);
            $imageName=$slug.'_'.Carbon::today()->toDateString().'_'.uniqid().'.'.$image->getClientOriginalExtension();

            // category image
            if(!Storage::disk('public')->exists('category')){
                Storage::disk('public')->makeDirectory('category');
            }
            Image::make($image)->resize(1600,479)->save(public_path('storage/category/'.$imageName));

            // category slider image
            if(!Storage::disk('public')->exists('category/slider')){
                Storage::disk('public')->makeDirectory('category/slider');
            }

            Image::make($image)->resize(500,333)->save(public_path('storage/category/slider/'.$imageName));
        }else{
            $imageName='default.png';
        }

        $category=new Category();
        $category->name=$request->name;
        $category->slug=$slug;
        $category->image=$imageName;
        $category->save();

        Session::flash('success', 'Category Successfully Saved :)');
        return redirect()->Route('admin.category.index');
    }

    public function show($id)
    {
        
    }

    public function edit($id)
    {
        $category=Category::find($id);
        return view('admin.category.edit',compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category=Category::find($id);
        $this->validate($request,[
            'name' => 'required',
            'image' => 'mimes:jpeg,bmp,png,jpg',
        ]);

        if($request->hasFile('image')){
            $image=$request->file('image');
            $slug=Str::slug($request->name);
            $imageName=$slug.'_'.Carbon::today()->toDateString().'_'.uniqid().'.'.$image->getClientOriginalExtension();

            // category image
            if(Storage::disk('public')->exists('category/'.$category
                ->image)){
                Storage::disk('public')->delete('category/'.$category
                ->image);
            }
            Image::make($image)->resize(1600,479)->save(public_path('storage/category/'.$imageName));

            // category slider image
            if(Storage::disk('public')->exists('category/slider/'.$category
                ->image)){
                Storage::disk('public')->delete('category/slider/'.$category
                ->image);
            }

            Image::make($image)->resize(500,333)->save(public_path('storage/category/slider/'.$imageName));
        }else{
            $imageName=$category->image;
        }

        $category->name=$request->name;
        $category->slug=Str::slug($request->name);
        $category->image=$imageName;
        $category->save();

        Session::flash('success', 'Category Successfully updated :)');
        return redirect()->Route('admin.category.index');
    }

    public function destroy($id)
    {
        $category=Category::find($id);

        if(Storage::disk('public')->exists('category/'.$category->image)){
            Storage::disk('public')->delete('category/'.$category->image);
        }

        if(Storage::disk('public')->exists('category/slider/'.$category->image)){
            Storage::disk('public')->delete('category/slider/'.$category->image);
        }

        $category->delete();

        Session::flash('success', 'Tag Successfully Deleted :)');
        return redirect()->back();
    }
}
