<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    public function index(){

        $locations = Location::all();
        $locations = Location::whereDate('created_at', today())
        // ->orderBy('created_at', 'desc')
        ->get();

        return view('map', ['locations'=>$locations]);
    }
    public function otherDaysTrail(Request $request){

        // $locations = Location::all();
        // $locations = Location::whereDate('created_at', )->get();
        $locations = Location::whereDate('created_at', Carbon::yesterday())
        ->orderBy('created_at', 'desc')->get();
        return view('mapotherdays', ['locations'=>$locations]);
    }
    public function showCurrentLocation(){
        $location = Location::orderBy('id', 'desc')->first();
        return view('currentlocation', ['location'=>$location]);
    }
}
