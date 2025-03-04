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
            <li class="breadcrumb-item active" aria-current="page">Drivers</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->


<div class="col-xl-12 row">
    <div class="card col-md-8 ">
        <div class="card-header">
            <h3 class="card-title">Add Driver</h3> 
        </div>
        <div class="card-body">
            <!--<p>Use <code class="highlighter-rouge">.table-striped</code>to add zebra-striping to any table row within the <code class="highlighter-rouge">.tbody</code>.</p>-->
            
                
                    <form action="/savedriver" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
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
                                <input type="name" class="form-control" value="{{ old('name', '') }}" name="name" id="name" placeholder="Name" autocomplete="name">
                            </div>
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" value="{{ old('phone', '') }}" name="phone"  placeholder="Phone" autocomplete="phone">
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" name="email" value="{{ old('email', '') }}" id="email" placeholder="Email" autocomplete="email">
                            </div>
                            {{-- <div class="form-group">
                                <label for="admin" class="form-label">User Type</label>
                                <select name="usertype" class="form-control form-select select2" data-bs-placeholder="Select User Type">
                                                    <option {{old('usertype','')=="admin"? 'selected':''}} value="admin">Admin</option>
                                                    <option {{old('usertype','')=="sales"? 'selected':''}} value="sales">Sales</option>
                                </select>
                            </div> --}}





                            <div class="form-group">
                                <label for="image" class="form-label">Image (Optional)</label>
                                <div class="col-lg-12 col-sm-12 mb-4 mb-lg-0">
                                    <input name="image" type="file" class="dropify" data-bs-height="180">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="password">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password"  autocomplete="password_confirmation">
                            </div>


                            
                            
                        </div>
                        <button class="btn btn-primary col-12 mt-4 mb-0">Save</button>
                    </form>
                
            </div>

        </div>
        <div class="col-md-3">

            <div class="card" style="width: 18rem;height:95%;background-color:#F7F7F9">
          <div class="card-body">
            <h5 class="card-title">Quick Tips</h5>
            <ol class="card-text">
            <li style="margin-bottom: 10px;"><strong>Name </strong>: Enter the user's name. Use letters and spaces only</li>
            <li style="margin-bottom: 10px;"><strong>Phone </strong>: Enter the user's phone number. Either format 0712345678 or +25471234678 is valid.</li>
            <li style="margin-bottom: 10px;"><strong>Email </strong>: Enter the user's email address. Use a valid email format (e.g., example@example.com).</li>
            {{-- <li style="margin-bottom: 10px;"><strong>User Type </strong>: Select the user type from the dropdown list. Choose between "Admin" or "Sales".</li> --}}
            <li style="margin-bottom: 10px;"><strong>Target Group </strong>: Select the target group from the dropdown list. Choose between various options, or select "None" if not applicable.</li>
            <li style="margin-bottom: 10px;"><strong>Password </strong>: Enter the desired password. Use a combination of letters, numbers, and special characters for security.</li>
            <li style="margin-bottom: 10px;"><strong>Confirm Password </strong>: Re-enter your password to confirm it matches the one entered above.</li>
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

        var territories  = @json($territories);

        $('#region_id').on('change', function() {
            var regionId = $(this).val();
            var $territorySelect = $('#territory_id');
            $territorySelect.empty();
            $territorySelect.append('<option value="0">None</option>');

            if (territories.length > 0) {
                territories.forEach(territory => {
                    // alert(territory.id+'">'+territory.name);

                    if(regionId==territory.region_id){
                    $territorySelect.append('<option value="'+territory.id+'">'+territory.name+'</option>');
                    }

                });
            }

        });

    
    });
    </script>