<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Subscriber;
use Session;

class SubscriberController extends Controller
{
    public function index(){
    	$subscribers=Subscriber::latest()->get();
    	return view('admin.subscriber',compact('subscribers'));
    }

    public function destroy($subscriber){
    	Subscriber::findOrFail($subscriber)->delete();

    	Session::flash('success', 'Subscriber Successfully Deleted :)');
        return redirect()->back();
    }
}
