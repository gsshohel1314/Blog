<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Tag;
use Session;

class TagController extends Controller
{
    public function index()
    {   
        $tags=Tag::latest()->get();
        return view('admin.tag.index',compact('tags'));
    }

    public function create()
    {
        return view('admin.tag.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
        ]);

        $tag=new Tag();
        $tag->name=$request->name;
        $tag->slug=Str::slug($request->name);
        $tag->save();

        Session::flash('success', 'Tag Successfully Saved');
        return redirect()->Route('admin.tag.index');
    }

    
    public function show($id)
    {
        
    }

    public function edit($id)
    {
        $tag=Tag::find($id);
        return view('admin.tag.edit',compact('tag'));
    }

    public function update(Request $request, $id)
    {
        $tag=Tag::find($id);
        $tag->name=$request->name;
        $tag->slug=Str::slug($request->name);
        $tag->save();

        Session::flash('success', 'Tag Successfully Updated');
        return redirect()->Route('admin.tag.index');
    }

    public function destroy($id)
    {
        Tag::find($id)->delete();

        Session::flash('success', 'Tag Successfully Deleted :)');
        return redirect()->back();
    }
}
