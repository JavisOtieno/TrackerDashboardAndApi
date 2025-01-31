<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    public function index(){

        $locations = Location::all();


        return view('map', ['locations'=>$locations]);
    }
    public function showCurrentLocation(){
        $location = Location::orderBy('id', 'desc')->first();
        return view('currentlocation', ['location'=>$location]);
    }
}
