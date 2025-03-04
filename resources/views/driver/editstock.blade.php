@extends('layout-form')

@section('content')

<div class="side-app">

    <!-- CONTAINER -->
    <div class="main-container container-fluid">

  <!-- PAGE-HEADER -->
  <div class="page-header">
    <h1 class="page-title">Sellers</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">RTMS</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Seller Stock</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->


<div class="col-xl-12 row">
    <div class="card col-md-8">
        <div class="card-header">
            <h3 class="card-title">Edit Seller Stock</h3> 
        </div>
        <div class="card-body">
            <!--<p>Use <code class="highlighter-rouge">.table-striped</code>to add zebra-striping to any table row within the <code class="highlighter-rouge">.tbody</code>.</p>-->
            
                
                    <form action="/saveeditsellerstock/{{$user['id']}}" method="POST" >
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

                                @foreach($products as $product)
                                <div class="form-group">
                                    <label for="stock-{{ $product['id'] }}" class="form-label">{{ $product['name'] }}</label>
                                    <input type="text" class="form-control" name="stocks[{{ $product['id'] }}]"
                                           id="stock-{{ $product['id'] }}" 
                                           placeholder="Enter stock" 
                                           value="{{ !empty($product['stocks']) && 
                                           isset($product['stocks'][0]['units']) ? 
                                           $product['stocks'][0]['units'] : '' }}" 
                                           autocomplete="stock">
                                </div>
                                @endforeach

   




                            </div>
                            
                        
                        <button class="btn btn-primary col-12 mt-4 mb-0">Save</button>
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
        <li style="margin-bottom: 10px;"><strong>Tips Section</strong> : Expect quick tips here</li>
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

    </script>