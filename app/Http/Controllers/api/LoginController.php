<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Rules\PhoneNumber;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class LoginController extends Controller
{

        public function doSignup(Request $request){
        
        $credentials = $request->validate([

            'name'=>'required|string|max:255',
            'phone'=>['required','min:10','string','max:255', new PhoneNumber],
            'email'=>'required|email|unique:users|max:255',
            //'password'=>'required|string|max:255|min:8|confirmed',
            'password' => ['required','string','max:255','min:8','confirmed',Password::min(8)->letters()->numbers()],
        ]);
        //return 'test';
        $incomingFields['status']='active';
        $incomingFields['name']=strip_tags($credentials['name']);
        $incomingFields['phone']=strip_tags($credentials['phone']);
        $incomingFields['usertype']='customer';
        $incomingFields['email']=strip_tags($credentials['email']);
        $incomingFields['password']=strip_tags($credentials['password']);



        //return "hello";

        //return $incomingFields;
        try{

        $user = User::create($incomingFields);
        
        // return $user->id;

        // Log the user in
               if (Auth::attempt($credentials)) {

            $userId = auth()->user()->id;

            $user = User::where('email',$request->email)->first();
            

            // $request->session()->regenerate();
            // return redirect()->intended('dashboard');
            if($user){
                
            $loginStatus=array(
                "message" => "Signup Successful",
                "status" => "success",
                // "test" => "test",
                'authToken' =>  $user->createToken('AuthToken')->plainTextToken,
                "userId"=>$userId);

                // if($user->organization->status=="inactive"){
                //     $loginStatus=array(
                //         "message" => "Organization Suspended",
                //         "status" => "failed",
                //         // "test" => "test",
                //         'authToken' =>  null,
                //         "userId"=>null);

                // }else if($user->status=='inactive'){
                //     $loginStatus=array(
                //     "message" => "Account Suspended",
                //         "status" => "failed",
                //         // "test" => "test",
                //         'authToken' =>  null,
                //         "userId"=>null);

                // }

            }else{
               
                $loginStatus=array(
                    "message" => "Signup Failed. Please try again",
                    "status" => "failed",
                    // "test" => "test",
                    'authToken' =>  null,
                    "userId"=>null);

            }
    
            
            
        }else{
            $loginStatus=array(
                "message" => "Login failed. Try logging in again",
                "status" => "failed");
        }

        } catch (\Exception $e) {
            // return back()->withInput()->with('error', 'An error occurred while creating the user. Please try again.');

                        $loginStatus=array(
                "message" => "An error occurred while creating the user. Please try again.",
                "status" => "failed");
        }

         return response()->json($loginStatus);

        

        //Initial design
        // if(User::count()==0){
        //     User::create($incomingFields);
        // }else{
        //     return redirect('signup')
        // ->withInput();
        // }

  

        
        
        // $credentials = $request->only('email', 'password');
        // return "we get here";
        // Auth::attempt($credentials);

        

        // $request->session()->regenerate();

        // return "hello";

        // return redirect('dashboard')
        // ->withSuccess('You have successfully registered & logged in!');

    }
    //
    public function doLogin(Request $request){
        
        //if email is not valid it returns null json object
        $credentials = $request->validate([
            'email'=>'required|email|max:255',
            'password'=>'required|string|max:255|min:8'
        ]);
        //return 'test';
        
        if (Auth::attempt($credentials)) {

            $userId = auth()->user()->id;

            $user = User::where('email',$request->email)->first();
            

            // $request->session()->regenerate();
            // return redirect()->intended('dashboard');
            if($user){
                
            $loginStatus=array(
                "message" => "Login Successful",
                "status" => "success",
                // "test" => "test",
                'authToken' =>  $user->createToken('AuthToken')->plainTextToken,
                "userId"=>$userId);

                // if($user->organization->status=="inactive"){
                //     $loginStatus=array(
                //         "message" => "Organization Suspended",
                //         "status" => "failed",
                //         // "test" => "test",
                //         'authToken' =>  null,
                //         "userId"=>null);

                // }else if($user->status=='inactive'){
                //     $loginStatus=array(
                //     "message" => "Account Suspended",
                //         "status" => "failed",
                //         // "test" => "test",
                //         'authToken' =>  null,
                //         "userId"=>null);

                // }

            }else{
               
                $loginStatus=array(
                    "message" => "Login Failed",
                    "status" => "failed",
                    // "test" => "test",
                    'authToken' =>  null,
                    "userId"=>null);

            }
    
            return response()->json($loginStatus);
            
        }else{
            $loginStatus=array(
                "message" => "Invalid Login Details",
                "status" => "failed");
        }


        return response()->json($loginStatus);
        
 
        // return back()->withErrors([
        //     'email' => 'The provided credentials do not match our records.',
        // ])->onlyInput('email');

    }
}
