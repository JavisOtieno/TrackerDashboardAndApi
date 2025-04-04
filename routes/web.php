<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignupController;
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

Route::middleware(['auth','verified'])->group(function () {

Route::get('/', [LocationController::class, 'index']);
Route::get('/locations', [LocationController::class, 'locationIndex']);
Route::get('/dailytrails', [LocationController::class, 'otherDaysTrail']);
Route::get('/currentlocation', [LocationController::class, 'showCurrentLocation']);
Route::get('/otherdays/{date}', [LocationController::class, 'getOtherDaysLocations']);

Route::get('/trips', [TripController::class, 'index']);
Route::get('/createtrip', [TripController::class,'addTrip']);
Route::get('/deletetrip/{id}', [TripController::class,'deleteTrip']);
Route::post('/savetrip',[TripController::class,'saveTrip']);
Route::get('/trip/{id}', [TripController::class,'showEditTrip']);
Route::put('/saveedittrip/{id}',[TripController::class,'saveEditTrip']);

Route::get('/admins', [UserController::class,'admins']);
Route::get('/admins/{id}/{object}',[UserController::class,'showAdminsById']);
Route::get('/deleteadmin/{id}', [UserController::class,'deleteAdmin']);
Route::post('/saveadmin', [UserController::class,'saveAdmin']);
Route::get('/add-admin', [UserController::class,'addAdmin']);
Route::get('/editadmin/{id}', [UserController::class,'showEditAdmin']);
Route::put('/saveeditadmin/{id}',[UserController::class,'saveEditAdmin']);

Route::get('/drivers', [UserController::class,'drivers']);
Route::get('/drivers/{id}/{object}',[UserController::class,'showDriversById']);
Route::get('/deletedriver/{id}', [UserController::class,'deleteDriver']);
Route::post('/savedriver', [UserController::class,'saveDriver']);
Route::get('/add-driver', [UserController::class,'addDriver']);
Route::get('/editdriver/{id}', [UserController::class,'showEditDriver']);
Route::put('/saveeditdriver/{id}',[UserController::class,'saveEditDriver']);


Route::get('/logout', [LoginController::class,'logout']);



});

Route::get('/login', [LoginController::class,'showLogin'])->name('login');
Route::post('/attemptlogin', [LoginController::class,'doLogin']);

Route::get('/signup', [SignupController::class,'showSignup'])->name('notwork');
Route::post('/attemptsignup', [SignupController::class,'doSignup']);

