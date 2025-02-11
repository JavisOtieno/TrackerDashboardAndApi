<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\LocationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [LocationController::class, 'index']);
Route::get('/currentlocation', [LocationController::class, 'showCurrentLocation']);

Route::get('/trips', [TripController::class, 'index']);
Route::get('/createtrip', [TripController::class,'addTrip']);
Route::get('/deletetrip/{id}', [TripController::class,'deleteTrip']);
Route::post('/savetrip',[TripController::class,'saveTrip']);
Route::get('/trip/{id}', [TripController::class,'showEditTrip']);
Route::put('/saveedittrip/{id}',[TripController::class,'saveEditTrip']);

