<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    //test 
    //test
    public function index(){

        // $locations = Location::all();
        $locations = Location::whereDate('created_at', today())
        ->orderBy('created_at', 'asc')
        ->get();

        $totalDistance = 0;

        function haversineDistance($lat1, $lon1, $lat2, $lon2, $unit = 'K')
        {
            $earthRadius = ($unit == 'K') ? 6371 : 3958.8; // Earth's radius in km or miles

            $lat1 = deg2rad($lat1);
            $lon1 = deg2rad($lon1);
            $lat2 = deg2rad($lat2);
            $lon2 = deg2rad($lon2);

            $dLat = $lat2 - $lat1;
            $dLon = $lon2 - $lon1;

            $a = sin($dLat / 2) * sin($dLat / 2) +
                cos($lat1) * cos($lat2) *
                sin($dLon / 2) * sin($dLon / 2);

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            return $earthRadius * $c;
        }

        if ($locations->count() > 1) { // Ensure we have at least two locations
            for ($i = 0; $i < count($locations) - 1; $i++) {
                $totalDistance += haversineDistance(
                    $locations[$i]->lat,
                    $locations[$i]->long,
                    $locations[$i + 1]->lat,
                    $locations[$i + 1]->long
                );
            }
        }

        return view('map', compact('locations','totalDistance'));
    }

    public function locationIndex(){

        // $locations = Location::all();
        $locations = Location::orderBy('created_at', 'desc')
        ->get();

        return view('location.index', compact('locations'));

    }
    public function getOtherDaysLocations($date,$driverid){

        // $locations = Location::all();
        $locations = Location::whereDate('created_at', $date)
        ->where('user_id',$driverid)
        ->orderBy('created_at', 'desc')->get();
        return response()->json($locations);
        
    }
    
    public function otherDaysTrail(Request $request){

        // $locations = Location::all();
        // $locations = Location::whereDate('created_at', )->get();
        
        $drivers = User::all();
        $driver = User::first();
        $locations = Location::whereDate('created_at', Carbon::today())
        ->where('user_id',$driver->id)
        ->orderBy('created_at', 'desc')->get();
        return view('mapotherdays', compact('locations','drivers'));
    }
    public function showCurrentLocation(){
        $location = Location::with('user')->orderBy('id', 'desc')->first();
        $drivers = User::all();

        $userswithcurrentlocations = User::with('latestLocation')->get();
        return view('currentlocation', compact('location','userswithcurrentlocations','drivers'));
    }
    public function getCurrentLocations(){
        $location = Location::with('user')->orderBy('id', 'desc')->first();

        $userswithcurrentlocations = User::with('latestLocation')->get();
        return response()->json($userswithcurrentlocations);
    }
}
