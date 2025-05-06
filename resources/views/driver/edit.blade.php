@extends('layout-form')

@section('content')

<div class="side-app">

    <!-- CONTAINER -->
    <div class="main-container container-fluid">

  <!-- PAGE-HEADER -->
  <div class="page-header">
    <h1 class="page-title">Drivers</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">RTMS</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Driver</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->


<div class="col-xl-12 row">
    <div class="card col-md-8">
        <div class="card-header">
            <h3 class="card-title">Edit Driver</h3> 
        </div>
        <div class="card-body">
            <!--<p>Use <code class="highlighter-rouge">.table-striped</code>to add zebra-striping to any table row within the <code class="highlighter-rouge">.tbody</code>.</p>-->
            
                
                    <form action="/saveeditdriver/{{$user['id']}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="">
                            @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>
                                            @if ($error == 'Any special error')
                                            Any special error
                                            @else
                                            {{ $error }}
                                            @endif
                                            </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="name" class="form-label">Name</label>
                                <input type="name" class="form-control" name="name" id="name" placeholder="Name" value="{{old('name',$user['name'])}}" autocomplete="name">
                            </div>
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone"  placeholder="Phone" value="{{old('phone',$user['phone'])}}" autocomplete="phone">
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="{{old('email',$user['email'])}}" autocomplete="email">
                            </div>
                            {{-- <div class="form-group">
                                <label for="admin" class="form-label">User Type</label>
                                <select name="usertype" class="form-control form-select select2" data-bs-placeholder="Select User Type">
                                                    <option {{old('usertype',$user['usertype'])=="admin"? 'selected':''}} value="admin">Admin</option>
                                                    <option {{old('usertype',$user['usertype'])=="sales"? 'selected':''}} value="sales">Sales</option>
                                </select>
                            </div> --}}
                            <
                           
                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" class="form-control form-select select2" data-bs-placeholder="Select Status">
                                    <option value="active" {{$user["status"]=='active'?'selected':'' }}>Active</option>
                                    <option value="inactive" {{$user["status"]=='inactive'?'selected':'' }}>Inactive</option>
                                </select>
                            </div>
                           
                            <div class="form-group">
                                <label for="dateTime" class="form-label">Date and Time Added</label>
                                <input readonly type="text" class="form-control" name="dateTime" id="dateTime" placeholder="DateTime" value="{{date('d-M-Y H:i', strtotime($user['created_at']))}}" autocomplete="dateTime">
                            </div>
                            <div class="form-group">
                                <label for="image" class="form-label">Image (Optional)</label>
                                {{-- {{url('storage/'.old('image',$user['image']))}} --}}
                                <div class="col-lg-12 col-sm-12 mb-4 mb-lg-0">
                                    <input type="file" class="dropify" name="image" data-default-file="{{$user['image']==''?'':url('storage/'.$user['image'])}}" data-bs-height="180">
                                    <input type="hidden" name="default_file_removed" id="default_file_removed" value="0">
                                    <input type="hidden" name="existing_file" value="{{$user['image']==''?'':url('storage/'.$user['image'])}}">
       
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="form-label">Password</label>
                                <a href="/changepassword/driver/{{$user['id']}}" class="col-11 btn btn-info mb-0">Change Password</a>                            
                            </div>
                          

                          

                            </div>
                            
                        </div>
                        <button class="btn btn-primary col-11 mt-4 mb-0">Save</button>
                    </form>

                    {{-- <div class="form-group" style="margin-top:20px;">
                        // <label for="password" class="form-label">Password</label> 
                        <form method="POST" action="{{ route('changepassword') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <!-- Other form fields -->
                            <button type="submit" class="btn btn-info">Change Password</button>
                        </form> --}}
                
            </div>

        </div>
    
    <div class="col-md-3">

        <div class="card" style="width: 18rem;height:95%;background-color:#F7F7F9">
      <div class="card-body">
        <h5 class="card-title">Quick Tips</h5>
        <ol class="card-text">
        <li style="margin-bottom: 10px;"><strong>Name</strong> : This field displays the name of the user and is editable.</li>
        <li style="margin-bottom: 10px;"><strong>Phone</strong> :This field displays the phone number of the user and is editable.</li>
        <li style="margin-bottom: 10px;"><strong>Email</strong> : This field displays the email address of the user and is editable.</li>
        {{-- <li style="margin-bottom: 10px;"><strong>User Type</strong> : This field displays the type of user (Admin or Sales). It is a dropdown menu where you can select the user type.</li> --}}
        <li style="margin-bottom: 10px;"><strong>Targets</strong> : This field displays the target group associated with the user. It is a dropdown menu where you can select the target group. </li>
        <li style="margin-bottom: 10px;"><strong>Date and Time Added</strong> : This field displays the date and time when the user was added. It is disabled and cannot be modified.</li>
        <li style="margin-bottom: 10px;"><strong>Password</strong> : This field provides a button to change the user's password. Clicking on the button will redirect you to a page where you can change the password for the user.</li>
        </ol>
    </div>
    </div>
    
        </div>
</div>
</div>

    </div>
</div>

@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {

    

    
    });
    </script>