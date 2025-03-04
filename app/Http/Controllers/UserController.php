<?php

namespace App\Http\Controllers;

// use App\Models\Team;
use App\Models\User;
// use App\Models\Stock;
// use App\Models\Region;
// use App\Models\Cluster;
// use App\Models\Product;
// use App\Models\Territory;
// use App\Rules\PhoneNumber;
// use App\Models\TargetGroup;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends CommonController
{
    //
    //test commit github
    public function apiindex(){
        //$sales = Sale::all();
        $users = User::get();
        return response()->json(['users'=>$users]);
    }

    public function admins() {
        $users = User::where('usertype','admin')->get();

        //return $users;
        //dd($sales);
        // $Drivers = TargetGroup::all();
        return view('admin.index', ['users'=>$users]);
    }
    public function drivers() {
        $users = User::where('usertype','driver')->get();

        //return $users;
        //dd($sales);
        // $Drivers = TargetGroup::withTrashed();

        return view('driver.index', ['users'=>$users]);
    }

    public function showDriversById($objectId,$object) {
        //$visits = Visit::all();
        // $targetgroups = TargetGroup::withTrashed();
        $drivers=User
        ::where($object.'_id',$objectId)
        ->where('usertype','driver')->get();
        return view('driver.index', ['users'=>$drivers]);
    }

    public function showAdminsById($objectId,$object) {
        //$visits = Visit::all();

        $admins=User::
        where($object.'_id',$objectId)
        ->where('usertype','admin')->get();
        return view('admin.index', ['users'=>$admins
         ]);
    }


    





    
    public function deleteDriver($id){
        $user = User::where('usertype','driver')->find($id);
        $user->delete();
        return redirect('/drivers');
    }



    public function deleteAdmin($id){
        $user = User::where('usertype','admin')->find($id);
        $user->delete();
        return redirect('/admins');
    }
    public function saveAdmin(Request $request){
        $organizationId=auth()->user()->organization_id;
       
        $incomingFields=$request->validate([
            'name'=>'required|string|max:255',
            'phone'=>['required','min:10','string','max:255', new PhoneNumber],
            // 'usertype'=>'required|string|max:255',
            'email'=>'required|email|unique:users|max:255',
            //'password'=>'required|string|max:255|min:8|confirmed',
            'password' => ['required','string','max:255','min:8','confirmed',Password::min(8)->letters()->numbers()],
            // 'target_group_id'=>'required|numeric|digits_between:1,11'
            
        ]);
        //return $incomingFields;
        
        
        $incomingFields['name']=strip_tags($incomingFields['name']);
        $incomingFields['phone']=strip_tags($incomingFields['phone']);
        // $incomingFields['usertype']='admin';
        // $incomingFields['target_group_id']=0;
        // $incomingFields['region_id']=0;
        // $incomingFields['territory_id']=0;
        $incomingFields['status']='active';
        $incomingFields['email']=strip_tags($incomingFields['email']);
        $incomingFields['password']=strip_tags($incomingFields['password']);
        $incomingFields['organization_id']=$organizationId;
        

        //return $incomingFields['usertype'];

        if($request['image']){

            $imageName = time().'.jpg';

            // Save image to public/images directory
            //$request->image->move(public_path('images'), $imageName);
    
            //code from android version
            $folder="users";
            $imagePathOnDB=$folder."/".$imageName;
            //Storage::disk('public')->put($imagePath, $imageData);
            $incomingFields['image']=$imagePathOnDB;
    
            $image = $request->file('image');
            $imagePath = $image->getPathName();

            $compressedImagePath = storage_path('app/public/users/'.$imageName);

            $sourcePath = $imagePath;
            $maxSizeKB = 100;
            $destinationPath = $compressedImagePath;

            $this->compressImage($sourcePath,$destinationPath,$maxSizeKB);
            
        }else{
            $incomingFields['image']='';   
        }

        User::create($incomingFields);

        return redirect('/admins');
        
    }





    public function saveDriver(Request $request){
        // $teamId = $this->teamIdCheckAssign($request);

        // $organizationId=auth()->user()->organization_id;
       
        $incomingFields=$request->validate([
            'name'=>'required|string|max:255',
            'phone'=>['required','min:10','string','max:255', new PhoneNumber],
            // 'usertype'=>'required|string|max:255',
            'email'=>'required|email|unique:users|max:255',
            //'password'=>'required|string|max:255|min:8|confirmed',
            'password' => ['required','string','max:255','min:8','confirmed',Password::min(8)->letters()->numbers()],
            // 'target_group_id'=>'required|numeric|digits_between:1,20',
            // 'region_id'=>'required|numeric|digits_between:1,20',
            // 'territory_id'=>'required|numeric|digits_between:1,20',
            // 'cluster_id'=>'required|numeric|digits_between:1,20',
            'image' => 'image|mimes:jpeg,webp,png,jpg,gif,svg|max:3072',
        ]);
        //return $incomingFields;
        
        
        $incomingFields['name']=strip_tags($incomingFields['name']);
        $incomingFields['phone']=strip_tags($incomingFields['phone']);
        $incomingFields['usertype']='driver';
        // $incomingFields['target_group_id']=strip_tags($incomingFields['target_group_id']);
        // $incomingFields['region_id']=strip_tags($incomingFields['region_id']);
        // $incomingFields['territory_id']=strip_tags($incomingFields['territory_id']);
        // $incomingFields['cluster_id']=strip_tags($incomingFields['cluster_id']);
        $incomingFields['email']=strip_tags($incomingFields['email']);
        $incomingFields['password']=strip_tags($incomingFields['password']);
        // $incomingFields['organization_id']=$organizationId;
        // $incomingFields['team_id']=$teamId;
        $incomingFields['status']='active';

        //return $request->image;
        // return $incomingFields;


        if($request['image']){
            
            $imageName = time().'.jpg';
            $folder="users";
            $imagePathOnDB=$folder."/".$imageName;
            $incomingFields['image']=$imagePathOnDB;
    
            $image = $request->file('image');
            $imagePath = $image->getPathName();

            $compressedImagePath = storage_path('app/public/users/'.$imageName);

            $sourcePath = $imagePath;
            $maxSizeKB = 100;
            $destinationPath = $compressedImagePath;

            $this->compressImage($sourcePath,$destinationPath,$maxSizeKB);

            
        }else{
            $incomingFields['image']='';   
        }
        

        //return $incomingFields['usertype'];
        // return $incomingFields['team_id'];

        User::create($incomingFields);

        return redirect('/drivers');
        
    }

    public function addDriver() {
        // $targetgroups = TargetGroup::all();
        // $regions = Region::all();
        // $territories = Territory::all();
        // $teams = Team::all();
        // $clusters = Cluster::all();
        return view('driver.create');
    }
    public function addAdmin() {
        return view('admin.create');
    }

    // public function uploadDriverProducts() {
    //     return view('driver.uploaddriverproducts');
    // }
    // public function editDriverStock($id){

    //     $user = User::inOrganization()->find($id);
    //     $products = Product::with(['stocks' 
    //     => function ($query) use ($id) {
    //         $query->where('user_id', $id);
    //     }
    //     ])
    //     // ->whereHas('stocks', function ($query) use ($id) {
    //     //     $query->where('user_id', $id) // Filter by driver
    //     //         ->where('units', '>', 0); // Include only positive stock
    //     // })
    //     ->orWhere('stock', '>', '0')
    //     ->orWhere('producttype', 'service') // Include products of type service
    //     ->orderBy('id', 'DESC')
    //     ->get();

    //     // return $products;

    //     return view('driver.editstock',compact('products','user'));
    // }

    // public function saveEditDriverStock($id,Request $request){

    //     $user = User::inOrganization()->find($id);

    //     // return $request;

    //     $validatedData = $request->validate([
    //         'stocks' => 'required|array', // Ensure 'stocks' is an array
    //         'stocks.*' => 'nullable|integer|min:0', // Each value must be a non-negative integer
    //     ]);

    //     // return $validatedData;
    //     // $test = "";
    
    //     foreach ($validatedData['stocks'] as $productId => $stock) {
    //         // Save each stock value to the database
    //         $stockobject = Stock::where('product_id', $productId)->where('user_id',$id)->first();

    //         if($stockobject == null && $stock != null){
    //             $incomingFields = [
    //                 'product_id' => $productId,
    //                 'user_id' => $id,
    //                 'units' => $stock
    //             ];
    //             Stock::create($incomingFields);
    //             // $test = $test.$productId." nullstockobject<br/>";
    //         }else if( $stockobject != null ){
    //             $stockobject->update(['units' => $stock]);
    //             // $test = $test.$productId." actualstockobject<br/>";
    //         }
            
    //     }

    //     // return $test;


    //     return redirect('/editdriverstock/'.$id); 

    // }


    public function showEditDriver($id){
        $user = User::find($id);
        // $targetgroups = TargetGroup::all();
        // $regions = Region::all();
        // $teams = Team::all();
        // $territories = Territory::all();
        // $clusters = Cluster::all();
        return view('driver.edit',compact('user'));
    }
    public function showEditAdmin($id){
        $user = User::find($id);
        return view('admin.edit',compact('user'));
    }

    // public function showEditUser(Request $request){
    //     //$user = User::inOrganization()->find($id);
    //     $incomingFields=$request->validate([
    //         'id'=>'required|numeric|digits_between:1,11'
    //     ]);
    //     $user = User::inOrganization()->find($incomingFields['id']);
    //     $targetgroups = TargetGroup::all();
    //     return view('edit-user',compact('user','targetgroups'));
    // }

    public function saveEditDriver($id, Request $request){
        // $teamId = $this->teamIdCheckAssign($request);

        // $organizationId=auth()->user()->organization_id;
        $incomingFields=$request->validate([
            'name'=>'required|string|max:255',
            'phone'=>['required','min:10','string','max:255', new PhoneNumber],
            //'usertype'=>'required|string|max:255',
            // 'email'=>'required|email|max:255',
            // 'email'=>'required|email|unique:users|max:255',
            
            'image' => 'image|mimes:jpeg,webp,png,jpg,gif,svg|max:3072',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            // 'target_group_id'=>'required|numeric|digits_between:1,20',
            // 'region_id'=>'required|numeric|digits_between:1,20',
            // 'territory_id'=>'required|numeric|digits_between:1,20',
            // 'cluster_id'=>'required|numeric|digits_between:1,20',
            'status'=>'required|string|max:255',
        ]);

        $incomingFields['name']=strip_tags($incomingFields['name']);
        //$incomingFields['usertype']='driver';
        $incomingFields['email']=strip_tags($incomingFields['email']);
        // $incomingFields['target_group_id']=strip_tags($incomingFields['target_group_id']);
        $incomingFields['phone']=strip_tags($incomingFields['phone']);
        // $incomingFields['organization_id']=$organizationId;
        // $incomingFields['region_id']=strip_tags($incomingFields['region_id']);
        // $incomingFields['territory_id']=strip_tags($incomingFields['territory_id']);
        // $incomingFields['cluster_id']=strip_tags($incomingFields['cluster_id']);
        // $incomingFields['team_id']=$teamId;
        $incomingFields['status']=strip_tags($incomingFields['status']);

        // return $incomingFields;

        $user=User::find($id);


        if ($request->input('default_file_removed') == 1 && !$request->hasFile('image')) {

            //return "default image removed and none uploaded";
            // Default file has been removed, handle accordingly
            $path = "";
            $incomingFields['image']=$path;
            // Optionally delete the old file if needed
            if ($request->input('existing_file')) {
                Storage::disk('public')->delete($request->input('existing_file'));
            }

        } elseif ($request->hasFile('image')) {
            //return "image uploaded";
            // Store the new file
            // $file = $request->file('file');
            // $path = $file->store('uploads', 'public');

            $request->validate([
                'image' => 'image|mimes:jpeg,webp,png,jpg,gif,svg|max:2048'
            ]);
            // old code
            // $imageName = time().'.'.$request->image->extension();
            // $folder="users";
            // $imagePath=$folder."/".$imageName;
            // $incomingFields['image']=$imagePath;
            // $path = $request->file('image')->storeAs($folder, $imageName, 'public');
            // old code

            $imageName = time().'.jpg';
            $folder="users";
            $imagePathOnDB=$folder."/".$imageName;
            $incomingFields['image']=$imagePathOnDB;

            $image = $request->file('image');
            $imagePath = $image->getPathName();

            $compressedImagePath = storage_path('app/public/users/'.$imageName);

            $sourcePath = $imagePath;
            $maxSizeKB = 100;
            $destinationPath = $compressedImagePath;

            $this->compressImage($sourcePath,$destinationPath,$maxSizeKB);

            // Optionally delete the old file if it exists
            if ($request->input('existing_file')) {
                Storage::disk('public')->delete($request->input('existing_file'));
            }

        } else {
            //return "image has not been changed";
            // Use the existing file path
            $path = $request->input('existing_file');
            // no need to access this
            // $incomingFields['image']=$path;
        }

        $user->update($incomingFields);

        return redirect('/drivers');
        
    }

    public function saveEditAdmin($id, Request $request){
        // $organizationId=auth()->user()->organization_id;
      
        $incomingFields=$request->validate([
            'name'=>'required|string|max:255',
            'phone'=>['required','min:10','string','max:255', new PhoneNumber],
            //'usertype'=>'required|string|max:255',
            // 'email'=>'required|email|max:255',
            // 'email'=>'required|email|unique:users|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            // 'target_group_id'=>'required|numeric|digits_between:1,11'
        ]);

        $incomingFields['name']=strip_tags($incomingFields['name']);
        //$incomingFields['usertype']='admin';
        $incomingFields['email']=strip_tags($incomingFields['email']);
        // $incomingFields['target_group_id']=strip_tags($incomingFields['target_group_id']);
        $incomingFields['phone']=strip_tags($incomingFields['phone']);
        // $incomingFields['organization_id']=$organizationId;

        $user=User::find($id);

        if ($request->input('default_file_removed') == 1 && !$request->hasFile('image')) {

            //return "default image removed and none uploaded";
            // Default file has been removed, handle accordingly
            $path = "";
            $incomingFields['image']=$path;
            // Optionally delete the old file if needed
            if ($request->input('existing_file')) {
                Storage::disk('public')->delete($request->input('existing_file'));
            }

        } elseif ($request->hasFile('image')) {
            //return "image uploaded";
            // Store the new file
            // $file = $request->file('file');
            // $path = $file->store('uploads', 'public');

            $request->validate([
                'image' => 'image|mimes:jpeg,webp,png,jpg,gif,svg|max:2048'
            ]);


            // $imageName = time().'.'.$request->image->extension();
            // $folder="users";
            // $imagePath=$folder."/".$imageName;
            // $incomingFields['image']=$imagePath;
            // $path = $request->file('image')->storeAs($folder, $imageName, 'public');

            

            $imageName = time().'.jpg';
            $folder="users";
            $imagePathOnDB=$folder."/".$imageName;
            $incomingFields['image']=$imagePathOnDB;

            $image = $request->file('image');
            $imagePath = $image->getPathName();

            $compressedImagePath = storage_path('app/public/users/'.$imageName);

            $sourcePath = $imagePath;
            $maxSizeKB = 100;
            $destinationPath = $compressedImagePath;

            $this->compressImage($sourcePath,$destinationPath,$maxSizeKB);



            // Optionally delete the old file if it exists
            if ($request->input('existing_file')) {
                Storage::disk('public')->delete($request->input('existing_file'));
            }

        } else {
            //return "image has not been changed";
            // Use the existing file path
            $path = $request->input('existing_file');
            // no need to access this
            // $incomingFields['image']=$path;
        }

        $user->update($incomingFields);

        return redirect('/admins');
        
    }

    public function showChangePassword($usertype,$id){
        $user = User::find($id);
        return view('changepassword',compact('usertype','user'));
    }

    // public function showChangePassword(Request $request){
    //     //$user = User::inOrganization()->find($id);
    //     $incomingFields=$request->validate([
    //         'id'=>'required|numeric|digits_between:1,11'
    //     ]);
    //     $user = User::inOrganization()->find($incomingFields['id']);
    //     return view('changepassword',['user'=>$user]);
    // }
    

    public function saveChangePassword($id, Request $request){
        $incomingFields=$request->validate([
            'password'=>'required|confirmed|min:8',
            'usertype'=>'required|string|in:admin,driver,teamlead'
        ]);

        $incomingFields['password']=strip_tags($incomingFields['password']);
        $usertype=strip_tags($incomingFields['password']);

        $user=User::find($id);

        $user->update($incomingFields);

        //return redirect('/edituser/'.$id);
        if($usertype=='admin'){
        return redirect('/admins');
        }
        // else if($usertype=='teamleads'){
        //     return redirect('/teamleads');
        //     }
        {
            return redirect('/drivers');
        }
        
    }

    public function showProfile(){
        // if(Auth::check()) 
        // {
        // $user_id=Auth::user()->id;
        // }
        $user = User::find(auth()->user()->id);
        return view('edit-profile',['user'=>$user]);
    }

    

}
