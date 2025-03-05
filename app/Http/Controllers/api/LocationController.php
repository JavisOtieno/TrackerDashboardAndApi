<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    //
    public function addLocation(Request $request){


       
        $incomingFields=$request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'trip_id' => 'nullable|numeric|digits_between:1,11',
            'long' => 'required|numeric|between:-180,180',
            'distance' => 'nullable|numeric',
            'accuracy' => 'nullable|numeric'
        ]);
        $userid = auth()->user()->id;
        $incomingFields['user_id']=$userid;
        

        // $incomingFields['user_id']=auth()->user()->id;

        Location::create($incomingFields);

        $locationStatus=array(
            "message" => "Location Added Successfully",
            "status" => "success");

        return response()->json($locationStatus);
    }
    public function index(Request $request){

        // $locations = Location::all();
        $locations = Location::whereDate('created_at', today())
        ->orderBy('created_at', 'asc')
        ->get();
        return response()->json($locations);
        
    }
    public function getOtherDaysLocations($date){

        // $locations = Location::all();
        $locations = Location::whereDate('created_at', $date)
        ->orderBy('created_at', 'desc')->get();
        return response()->json($locations);
        
    }

    public function getCurrentLocation(Request $request){

        $location = Location::orderBy('id', 'desc')->first();
        return response()->json($location);
        
    }
}
