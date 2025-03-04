<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    //
    public function index(){

        $userid = auth()->user()->id;

        $user = User::where('user_id', $userid)->orderBy('created_at', 'desc')->first();

        return response()->json(['user'=>$user]);
        
    }
}
