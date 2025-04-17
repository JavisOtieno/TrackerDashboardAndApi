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
        $trips = Trip::with('locations')->withSum('locations', 'distance')->orderBy('created_at', 'desc')->get();
        $tripselected = Trip::with('locations')->find(19);

        $locations = Location::where('trip_id','19')
        ->orderBy('created_at', 'asc')
        ->get();

        $totalDistance = 0;

        // function haversineDistance($lat1, $lon1, $lat2, $lon2, $unit = 'K')
        // {
        //     $earthRadius = ($unit == 'K') ? 6371 : 3958.8; // Earth's radius in km or miles

        //     $lat1 = deg2rad($lat1);
        //     $lon1 = deg2rad($lon1);
        //     $lat2 = deg2rad($lat2);
        //     $lon2 = deg2rad($lon2);

        //     $dLat = $lat2 - $lat1;
        //     $dLon = $lon2 - $lon1;

        //     $a = sin($dLat / 2) * sin($dLat / 2) +
        //         cos($lat1) * cos($lat2) *
        //         sin($dLon / 2) * sin($dLon / 2);

        //     $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        //     return $earthRadius * $c;
        // }
        // $distanceComparisons = '';

        // if ($locations->count() > 1) { // Ensure we have at least two locations
        //     for ($i = 0; $i < count($locations) - 1; $i++) {
        //         $currentDistance = haversineDistance(
        //             $locations[$i]->lat,
        //             $locations[$i]->long,
        //             $locations[$i + 1]->lat,
        //             $locations[$i + 1]->long
        //         );
        //         $totalDistance += $currentDistance;
        //         $distanceComparisons .= $currentDistance.' '.$locations[$i+1]->distance.' '.$totalDistance.'<br/>';
        //     }
        // }

        // $totalDistance = 0;

        // $previousLocation = null;
        // foreach ($tripselected->locations as $location) {
        //     if ($previousLocation) {
        //         $totalDistance += abs($location->distance - $previousLocation->distance);
        //     }
        //     $previousLocation = $location;
        // }

        // return $totalDistance;
        // return $distanceComparisons;

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
        // foreach ($trips as $trip) {
            $trip = Trip::where('id',19);

            $locations = Location::where('trip_id', $trip->id)->orderBy('created_at')->get();

            $firstlocation = $locations->first();
            $lastlocation = $locations->last();

            if($firstlocation == null){
                $firstdistance = 0;
                $lastdistance = 0;
            }else{
                $firstdistance = $this->haversineDistance($trip->start_lat,$trip->start_long,$firstlocation->lat,$firstlocation->long);
                $lastdistance = $this->haversineDistance($lastlocation->lat,$lastlocation->long,$trip->end_lat,$trip->end_long);
            }
            foreach ($locations as $location) {
                $currentdistance = $this->haversineDistance($firstlocation->lat,$firstlocation->long,
                $location->lat,$location->long);
                $firstlocation = $location;
                $viewresults .= 'calcdist '.$currentdistance.' lat '.$location->lat.' long'.$location->long.' locdist'.$location->distance.'<br/>';
             }


            $totalDistance =Location::where('trip_id', $trip->id)
            ->sum('distance');
            $finaltotaldistance = $totalDistance+$firstdistance+$lastdistance;
            $incomingFields['distance'] = $finaltotaldistance;
            $viewresults .= $finaltotaldistance.' '.$totalDistance.' '.$firstdistance.' '.$lastdistance.'<br/>';

            $trip->update($incomingFields);


        // }
        return $viewresults;
    }
}
