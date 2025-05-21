<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\CommonController;

class TripController extends CommonController
{
    //
    //test
    public function index(){

        // $trips = Trip::all();
        // test
        $trips = Trip::with('locations')->where('status', '!=', 'order')
        ->withSum('locations', 'distance')->orderBy('created_at', 'desc')->get();
        $tripselected = Trip::with('locations')->find(19);

        $locations = Location::where('trip_id','19')
        ->orderBy('created_at', 'asc')
        ->get();

        $totalDistance = 0;

        return view('trip.index', ['trips'=>$trips]);
    }

        public function indexOrders(){

        // $trips = Trip::all();
        // test
        $trips = Trip::with('locations')->where('status', 'order')
        ->withSum('locations', 'distance')->orderBy('created_at', 'desc')->get();
        $tripselected = Trip::with('locations')->find(19);

        $locations = Location::where('trip_id','19')
        ->orderBy('created_at', 'asc')
        ->get();

        $totalDistance = 0;

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
        //test
        //test


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

    public function tempSumTripLocations(){
        $trips = Trip::all();
        $viewresults = '';
        foreach ($trips as $trip) {
            // $trip = Trip::find(19);

            // $locations = Location::where('user_id', 13)->orderBy('created_at')->get();

            $locations = Location::where('trip_id', $trip->id)->get();
            // ->whereBetween('created_at', ['2027-03-26 00:00:00', '2027-03-30 23:59:59'])->orderBy('created_at')->get();

            //totaldist
            $totaldist = 0;

            $firstlocation = $locations->first();
            $lastlocation = $locations->last();

            foreach ($locations as $location) {
                $currentdistance = $this->haversineDistance($firstlocation->lat,$firstlocation->long,
                $location->lat,$location->long);
                $totaldist += $currentdistance;
                $firstlocation = $location;
                    // Get total distance from DB up to this point (based on created_at timestamp)
                $totalDistanceSum = Location::where('trip_id', $trip->id)
                ->where('created_at', '<=', $location->created_at)
                ->sum('distance');

                if(round($currentdistance, 5)!=round($location->distance, 5) || $location->distance==null ){
                    $location->distance = $currentdistance;
                    $location->save();
                    $viewresults .='<br/>';
                }
                $viewresults .= 'calcdist '.$currentdistance.' lat '.$location->lat.' long'.$location->long.' locdist'.$location->distance.' created at'.$location->created_at.' totaldist '.$totaldist.' totaldistsum '.$totalDistanceSum.'<br/>';

             }

             $locations = Location::where('trip_id', $trip->id)->orderBy('id')->get() ;
             $firstlocation = $locations->first();
             $lastlocation = $locations->last();
 
             if($firstlocation == null){
                 $firstdistance = 0;
                 $lastdistance = 0;
             }else{
                 $firstdistance = $this->haversineDistance($trip->start_lat,$trip->start_long,$firstlocation->lat,$firstlocation->long);
                 $lastdistance = $this->haversineDistance($lastlocation->lat,$lastlocation->long,$trip->end_lat,$trip->end_long);
             }


             $distances = Location::where('trip_id', $trip->id)
             ->orderBy('id')        // or whatever column defines the "first" record
             ->pluck('distance');   // returns a Collection of distances
         
            // 2. Skip the first item, then sum
            $totalDistance = $distances
             ->skip(1)              // alias of ->slice(1)
             ->sum();
                  
            $finaltotaldistance = $totalDistance+$firstdistance+$lastdistance;
            $incomingFields['distance'] = $finaltotaldistance;
            $viewresults .= $finaltotaldistance.' '.$totalDistance.' '.$firstdistance.' '.$lastdistance.'<br/><br/><br/>';

            $trip->update($incomingFields);


        }
        return $viewresults;
    }
}
