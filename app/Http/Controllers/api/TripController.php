<?php

namespace App\Http\Controllers\api;

use App\Models\Trip;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;

class TripController extends CommonController
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
        $incomingFields['status']='start';

        // $incomingFields['name']=strip_tags($incomingFields['name']);

        //return $incomingFields['usertype'];

        // Save Trip
        $trip = Trip::create($incomingFields);
 
        // Save start location
        // Location::create([
        //     'trip_id' => $trip->id,
        //     'user_id' => $userid,
        //     'lat' => $incomingFields['start_lat'],
        //     'long' => $incomingFields['start_long'],
        //     'name' => $incomingFields['start_location'],
        //     'type' => 'start',
        // ]);

        $tripStatus=array(
            "message" => "Trip Added Successfully",
            "tripId" => $trip->id,
            "status" => "success");

        return response()->json($tripStatus);
        
    }

    public function index(){

        $userid = auth()->user()->id;

        $trips = Trip::where('user_id', $userid)->orderBy('created_at', 'desc')->get();

        return response()->json(['trips'=>$trips]);
        
    }

    public function show($id){
        
        $trip = Trip::with(['locations' => function ($query) {
            $query->where('type', 'stopover');
        }])->find($id);
        return response()->json(['trip'=>$trip]);
        
    }

        public function startTrip($id, Request $request){

        $incomingFields=$request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'long' => 'required|numeric|between:-180,180',

        ]);

        $trip = Trip::find($id);
        $userId = auth()->user()->id;

        $incomingFields['status'] = 'start';
        $incomingFields['user_id']=$userId;

        $distance = $this->haversineDistance($trip->start_lat,$trip->start_long,
        $incomingFields['lat'],$incomingFields['long']);

        if($distance<0.040){

            // Update Trip
            $trip->update($incomingFields);

            // Save end point as a location
            // Location::create([
            //     'trip_id' => $trip->id,
            //     'user_id' => $userId,
            //     'lat' => $incomingFields['end_lat'],
            //     'long' => $incomingFields['end_long'],
            //     'name' => $trip->end_location,
            //     'type' => 'end',
            // ]);

            $tripStatus=array(
                "message" => "Trip Started Successfully",
                "tripId" => $trip->id,
                "status" => "success");


        }else{
                $tripStatus=array(
                "message" => "Move closer to the starting point",
                "tripId" => $trip->id,
                "status" => "error");

        }

        


        return response()->json($tripStatus);
        
    }

    public function endTrip($id, Request $request){

        $incomingFields=$request->validate([
            'end_lat' => 'required|numeric|between:-90,90',
            'end_long' => 'required|numeric|between:-180,180',

        ]);

        $trip = Trip::find($id);
        $userId = auth()->user()->id;

        $locations = Location::where('trip_id', $id)->orderBy('created_at')->get();

        // $firstlocation = $locations->first();
        // $lastlocation = $locations->last();

        // $firstdistance = $this->haversineDistance($trip->start_lat,$trip->start_long,$firstlocation->lat,$firstlocation->long);
        // $lastdistance = $this->haversineDistance($lastlocation->lat,$lastlocation->long,$incomingFields['end_lat'],$incomingFields['end_long']);

        $totalDistance =Location::where('trip_id', $id)
        ->sum('distance');
        $incomingFields['distance'] = $totalDistance;
        $incomingFields['status']='end';
        
        // Update Trip
        $trip->update($incomingFields);

        // Save end point as a location
        // Location::create([
        //     'trip_id' => $trip->id,
        //     'user_id' => $userId,
        //     'lat' => $incomingFields['end_lat'],
        //     'long' => $incomingFields['end_long'],
        //     'name' => $trip->end_location,
        //     'type' => 'end',
        // ]);

        $tripStatus=array(
            "message" => "Trip Ended Successfully",
            "tripId" => $trip->id,
            "status" => "success");

        return response()->json($tripStatus);
        
    }

    public function addStopOver(Request $request)
    {
        $incomingFields = $request->validate([
            'name' => 'required|string|max:255',
            'lat' => 'required|numeric|between:-90,90',
            'long' => 'required|numeric|between:-180,180',
            'trip_id' => 'required|numeric|exists:trips,id'
        ]);

        $incomingFields['user_id'] = auth()->user()->id;
        $incomingFields['type'] = 'stopover';

        Location::create($incomingFields);

        return response()->json([
            'message' => 'Stop over recorded successfully.',
            'status' => 'success'
        ]);
    }



        public function saveTripCustomer(Request $request){

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

        $incomingFields['status']='order';

        // $incomingFields['name']=strip_tags($incomingFields['name']);

        //return $incomingFields['usertype'];

        // Save Trip
        $trip = Trip::create($incomingFields);
 
        // Save start location
        // Location::create([
        //     'trip_id' => $trip->id,
        //     'user_id' => $userid,
        //     'lat' => $incomingFields['start_lat'],
        //     'long' => $incomingFields['start_long'],
        //     'name' => $incomingFields['start_location'],
        //     'type' => 'start',
        // ]);

        $tripStatus=array(
            "message" => "Trip Added Successfully",
            "tripId" => $trip->id,
            "status" => "success");

        return response()->json($tripStatus);
        
    }

    public function indexCustomer(){

        $userid = auth()->user()->id;

        $trips = Trip::where('status', 'order')->orderBy('created_at', 'desc')->get();

        return response()->json(['trips'=>$trips]);
        
    }

    public function showCustomer($id){
        
        $trip = Trip::with(['locations' => function ($query) {
            $query->where('type', 'stopover');
        }])->find($id);
        return response()->json(['trip'=>$trip]);
        
    }

}
