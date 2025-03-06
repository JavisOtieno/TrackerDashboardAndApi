<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    //
    public function index(){

        // $trips = Trip::all();
        $trips = Trip::with('locations')->withSum('locations', 'distance')->orderBy('created_at', 'desc')->get();
        $tripselected = Trip::with('locations')->find(17);

        $totalDistance = 0;

        $previousLocation = null;
        foreach ($tripselected->locations as $location) {
            if ($previousLocation) {
                $totalDistance += abs($location->distance - $previousLocation->distance);
            }
            $previousLocation = $location;
        }

        return $totalDistance;
        
        return view('trip.index', ['trips'=>$trips]);
    }
    public function deleteTrip($id){
        $trip = Trip::find($id);
        $trip->delete();
        return redirect('/trips');
    }
    public function addTrip() {
        return view('trip.create');
    }

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

        return redirect('/trips');
        
    }
    public function showEditTrip($id){
        $trip = Trip::find($id);
        return view('trip.edit',['trip'=>$trip]);
    }

    public function saveEditTrip($id, Request $request){
        
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

        // $incomingFields['name']=strip_tags($incomingFields['name']);

        $trip=Trip::find($id);

        $trip->update($incomingFields);

        return redirect('/trips');
        
    }
}
