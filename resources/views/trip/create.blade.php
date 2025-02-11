@extends('layout-form')

@section('content')

<div class="side-app">

    <!-- CONTAINER -->
    <div class="main-container container-fluid">

  <!-- PAGE-HEADER -->
  <div class="page-header">
    <h1 class="page-title">Trips</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">RTMS</a></li>
            <li class="breadcrumb-item active" aria-current="page">Trips</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->


<div class="col-xl-12 row">
    <div class="card col-md-8 ">
        <div class="card-header">
            <h3 class="card-title">Add Trip</h3> 
        </div>
        <div class="card-body">
            <!--<p>Use <code class="highlighter-rouge">.table-striped</code>to add zebra-striping to any table row within the <code class="highlighter-rouge">.tbody</code>.</p>-->
            
                
                    <form action="/savetrip" method="POST">
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
                                <label for="start_location" class="form-label">Start Location</label>
                                <input type="text" class="form-control" name="start_location" id="name" placeholder="Start Location" value="{{ old('start_location', '') }}" autocomplete="start_location">
                            </div>
                            <div class="form-group">
                                <label for="start_lat" class="form-label">Start Lat</label>
                                <input type="text" class="form-control" name="start_lat" id="start_lat" placeholder="Start Lat" value="{{ old('start_lat', '') }}" autocomplete="start_lat">
                            </div>

                            <div class="form-group">
                                <label for="start_long" class="form-label">Start Long</label>
                                <input type="text" class="form-control" name="start_long" id="start_long" placeholder="Start Long" value="{{ old('start_long', '') }}" autocomplete="start_lat">
                            </div>
                            <div class="form-group">
                                <label for="end_location" class="form-label">End Location</label>
                                <input type="text" class="form-control" name="end_location" id="name" placeholder="End Location" value="{{ old('end_location', '') }}" autocomplete="end_location">
                            </div>
                            <div class="form-group">
                                <label for="end_lat" class="form-label">End Lat</label>
                                <input type="text" class="form-control" name="end_lat" id="end_lat" placeholder="End Lat" value="{{ old('end_lat', '') }}" autocomplete="end_lat">
                            </div>

                            <div class="form-group">
                                <label for="end_long" class="form-label">End Long</label>
                                <input type="text" class="form-control" name="end_long" id="end_long" placeholder="End Long" value="{{ old('end_long', '') }}" autocomplete="end_long">
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label">Description</label>
                                <textarea type="text" class="form-control" name="description" id="description" placeholder="Description"  autocomplete="description">{{ old('description', '') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="end_long" class="form-label">Amount</label>
                                <input type="number" class="form-control" name="amount" id="amount" placeholder="amount" value="{{ old('amount', '') }}" autocomplete="amount">
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
                <li style="margin-bottom: 10px;"><strong>Name </strong>: Enter the name of the trip. </li>
            </ol>
          </div>
        </div>
        
            </div>
    </div>
</div>

    </div>
</div>

@endsection