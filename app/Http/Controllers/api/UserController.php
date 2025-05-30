<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //

        public function customers() {
        $customers = User::where('usertype','customer')->get();

        //return $users;
        //dd($sales);
        // $Customers = TargetGroup::withTrashed();

        return response()->json(['customers'=>$customers]);
    }

    
}
