<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscriber;
use Session;

class SubscriberController extends Controller
{
    public function store(Request $request){
    	$this->validate($request,[
    		'email' => 'required|email|unique:subscribers',
    	]);

    	$subscriber = new Subscriber();
    	$subscriber->email = $request->email;
    	$subscriber->save();

    	Session::flash('success', 'You successfully add to our subscriber list :)');
        return redirect()->back();
    }
}
