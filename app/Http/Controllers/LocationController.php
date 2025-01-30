<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    public function index(Request $request){

        $locations = Location::all();


        return view('map', ['locations'=>$locations]);
    }
}
