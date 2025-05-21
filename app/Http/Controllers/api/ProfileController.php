<?php

namespace App\Http\Controllers\api;

use App\Models\Trip;
use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    //
    public function index(){

        $userid = auth()->user()->id;

        $user = User::where('id', $userid)->orderBy('created_at', 'desc')->first();
        $tripscount = Trip::where('user_id', $userid) -> count();
        $locationscount = Location::where('user_id', $userid) -> count();
        $tripsamount = Trip::where('user_id', $userid)->sum('amount');

        return response()->json(compact('user','tripscount','locationscount','tripsamount'));
        
    }

        public function indexCustomer(){

        $userid = auth()->user()->id;

        $user = User::where('id', $userid)->orderBy('created_at', 'desc')->first();
        $tripscount = Trip::where('customer_id', $userid) -> count();
        // $locationscount = Location::where('id', $userid) -> count();
        $tripsamount = Trip::where('customer_id', $userid)->sum('amount');

        return response()->json(compact('user','tripscount','tripsamount'));
        
    }

    
}
