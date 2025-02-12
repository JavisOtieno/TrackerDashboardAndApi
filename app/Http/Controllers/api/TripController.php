<?php

namespace App\Http\Controllers\api;

use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TripController extends Controller
{
    //

    public function saveTrip(Request $request){
   
       
        $incomingFields=$request->validate([
            'start_location'=>'required|string|max:255',
            'start_lat'=>'required|string|max:255',
            'start_long'=>'required|string|max:255',
            'end_location'=>'required|string|max:255',
            'end_lat'=>'required|string|max:255',
            'end_long'=>'required|string|max:255',
            'description'=>'required|string|max:3000',
            'amount'=>'required|numeric|digits_between:1,11',

        ]);
        //return $incomingFields;

        
        
        // $incomingFields['name']=strip_tags($incomingFields['name']);

        //return $incomingFields['usertype'];

        Trip::create($incomingFields);

        $tripStatus=array(
            "message" => "Trip Added Successfully",
            "status" => "success");

        return response()->json($tripStatus);
        
    }
    public function index(Request $request){

        $trips = Trip::all();
        return response()->json($trips);
        
    }
}
