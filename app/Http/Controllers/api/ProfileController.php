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
        $tripscount = Trip::where('id', $userid) -> count();
        $locationscount = Location::where('id', $userid) -> count();
        $tripsamount = Trip::where('id', $userid)->sum('amount');

        return response()->json(compact('user','tripscount','locationscount','tripsamount'));
        
    }
}
