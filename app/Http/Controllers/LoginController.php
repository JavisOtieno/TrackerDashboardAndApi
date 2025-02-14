<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\Console\Input\Input;

class LoginController extends Controller
{

    public function showLogin(){

        //$usercount=User::inOrganization()->count();
        // return view('login',['usercount'=>$usercount]);
        return view('login');

    }
    public function logout(){
        Auth::logout();
        return redirect('login');
    }
    /*
    function doLogout()
      {
      Auth::logout(); // logging out user
      return Redirect::to('login'); // redirection to login screen
      }
      */
    public function doLogin(Request $request){
        
        $credentials = $request->validate([
            'email'=>'required|email|max:255',
            'password' => 'required|max:255|min:8|string',
        ]);
        //return 'test';
        
        
        if (Auth::attempt($credentials)) {

          if( 
            auth()->user()->usertype=='systemadmin' 
          || auth()->user()->usertype=='admin' ){
            $request->session()->regenerate();
            return redirect()->intended('/');
          }else{

            return back()->withErrors([
              'email' => 'The provided credentials do not match our records.',
              ])->onlyInput('email');

          }
  
        }
        // else if(User::inOrganization()->count()==0){
        //   return back()->withErrors([
        //     'email' => 'No account detected. Initiate first admin signup',
        //     ])->onlyInput('email');
        // }
        else{
          return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }
        
 
        

    }
    /*
    public
    function doLogin()
      {

      // Creating Rules for Email and Password
      $rules = array(
        'email' => 'required|email', // make sure the email is an actual email
        'password' => 'required|alphaNum|min:8'
      );
        // password has to be greater than 3 characters and can only be alphanumeric and);
        // checking all field
        $validator = Validator::make(Input::all() , $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails())
          {
          return Redirect::to('login')->withErrors($validator) // send back all errors to the login form
          ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
          }
          else
          {
          // create our user data for the authentication
          $userdata = array(
            'email' => Input::get('email') ,
            'password' => Input::get('password')
          );
          // attempt to do the login
          if (Auth::attempt($userdata))
            {
            // validation successful
            // do whatever you want on success
            }
            else
            {
            // validation not successful, send back to form
            return Redirect::to('login');
            }
          }
        }

        */
}
