<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\TripController;
use App\Http\Controllers\api\UserController;
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

Route::get('/profile', [ProfileController::class, 'index']);
Route::get('/profilecustomer', [ProfileController::class, 'indexCustomer']);

Route::post('/addlocation', [LocationController::class, 'addLocation']);

Route::post('/addtrip', [TripController::class, 'saveTrip']);
Route::get('/trips', [TripController::class, 'index']);
Route::get('/orders', [TripController::class, 'indexOrders']);
Route::get('/trip/{id}', [TripController::class, 'show']);

Route::post('/addtripcustomer', [TripController::class, 'saveTripCustomer']);
Route::get('/tripscustomer', [TripController::class, 'indexCustomer']);
Route::get('/tripscustomerorders', [TripController::class, 'indexCustomerOrders']);
Route::get('/tripspendingorders', [TripController::class, 'indexPendingOrders']);
Route::get('/tripcustomer/{id}', [TripController::class, 'showCustomer']);

Route::put('/endtrip/{id}', [TripController::class, 'endTrip']);
Route::put('/starttrip/{id}', [TripController::class, 'startTrip']);
Route::post('/addstopover', [TripController::class, 'addStopOver']);


Route::get('/customers', [UserController::class, 'customers']);

});


Route::post('/login', [LoginController::class,'doLogin']);
