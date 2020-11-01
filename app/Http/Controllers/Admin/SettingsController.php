<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use Carbon\Carbon;
// use Auth;
use Image;
use Session;
use Storage;

class SettingsController extends Controller
{
    public function index(){
    	return view('admin.settings');
    }

    public function updateProfile(Request $request){
    	$this->validate($request,[
    		'name'  => 'required',
    		'username'  => 'required',
    		'image' => 'image',
    	]);

    	$user=User::findOrFail(Auth::id());

    	if($request->hasFile('image')){
            $image=$request->file('image');
            $slug=Str::slug($request->name);
            $imageName=$slug.'-'.Carbon::today()->toDateString().'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('profile')){
                Storage::disk('public')->makeDirectory('profile');
            }

            if(Storage::disk('public')->exists('profile/'.$user->image)){
                Storage::disk('public')->delete('profile/'.$user->image);
            }
            Image::make($image)->resize(500,500)->save(public_path('storage/profile/'.$imageName));
        }else{
            $imageName=$user->image;
        }

        $user->name = $request->name;      
        $user->username = $request->username;      
        $user->image = $imageName;      
        $user->about = $request->about;      
        $user->save();      
        
        Session::flash('success', 'Profile Successfully Updated :)');
        return redirect()->back();     
    }

    public function updatePassword( Request $request){
    	$this->validate($request,[
    		'old_password' => 'required',
    		'password' => 'required|confirmed',
    	]);

    	$hashedPassword = Auth::User()->password;
    	if (Hash::check($request->old_password, $hashedPassword)) {
    		if (!Hash::check($request->password, $hashedPassword)) {
    			$user = User::find(Auth::id());
    			$user->password=Hash::make($request->password);
    			$user->save();

    			Session::flash('success', 'Password Successfully Updated :)');
    			Auth::logout();
        		return redirect()->back();
    		}else{
    			Session::flash('error', "New password can't be the same as old password");
        		return redirect()->back(); 
    		}
    	}else{
    		Session::flash('error', "Current password dose not match");
    		return redirect()->back();
    	}
    }
}
