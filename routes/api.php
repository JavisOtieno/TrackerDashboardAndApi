<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\TripController;
use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\api\ProfileController;
use App\Http\Controllers\api\LocationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//testing

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth','verified'])->group(function () {
    Route::get('/', [LocationController::class, 'index']);
    Route::get('/otherdays/{date}', [LocationController::class, 'getOtherDaysLocations']);
    Route::get('/currentlocation', [LocationController::class, 'getCurrentLocation']);
});


Route::middleware(['auth:sanctum'])->group(function(){

Route::post('/profile', [ProfileController::class, 'index']);

Route::post('/addlocation', [LocationController::class, 'addLocation']);

Route::post('/addtrip', [TripController::class, 'saveTrip']);
Route::get('/trips', [TripController::class, 'index']);
Route::get('/trip/{id}', [TripController::class, 'show']);
Route::put('/endtrip/{id}', [TripController::class, 'endTrip']);

});

Route::post('/login', [LoginController::class,'doLogin']);
