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
            'description'=>'required|string|max:3000',
            'amount'=>'required|numeric|digits_between:1,11',

        ]);
        //return $incomingFields;
        $userid = auth()->user()->id;
        $incomingFields['user_id']=$userid;

        // $incomingFields['name']=strip_tags($incomingFields['name']);

        //return $incomingFields['usertype'];

        $trip = Trip::create($incomingFields);
 
        $tripStatus=array(
            "message" => "Trip Added Successfully",
            "tripId" => $trip->id,
            "status" => "success");

        return response()->json($tripStatus);
        
    }
    public function index(){

        $trips = Trip::orderBy('created_at', 'desc')->get();
        return response()->json(['trips'=>$trips]);
        
    }
    public function show($id){
        $trip = Trip::find($id);
        return response()->json(['trip'=>$trip]);
        
    }
    public function endTrip($id, Request $request){
        $incomingFields=$request->validate([
            'end_lat' => 'required|numeric|between:-90,90',
            'end_long' => 'required|numeric|between:-180,180',

        ]);
        $trip = Trip::find($id);
        $trip->update($incomingFields);

        $tripStatus=array(
            "message" => "Trip Ended Successfully",
            "tripId" => $trip->id,
            "status" => "success");

        return response()->json($tripStatus);
        
    }

}
