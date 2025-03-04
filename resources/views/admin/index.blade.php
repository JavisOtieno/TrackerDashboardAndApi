@extends('layout')


@section('content')

<div class="side-app">

    <!-- CONTAINER -->
    <div class="main-container container-fluid">

  <!-- PAGE-HEADER -->
  <div class="page-header">
    <h1 class="page-title">Admins</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">RTMS</a></li>
            <li class="breadcrumb-item active" aria-current="page">Admins</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->


        <!-- Row -->
        
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                    
                        <h3 class="card-title">Admins</h3>
                        <a type="button" href="/add-admin" class="btn btn-danger col-md-3" style="margin-left: auto;margin-right:20px;margin-top:10px;margin-bottom:10px;color:white;">Add Admin</a>
                    
                        
                    </div>
                    <div class="row" style="margin: 0 10px;">
                        <input type="hidden" id="tableLoaded" value="users"/>
                        <h3 class="card-title" style="margin-top:20px;margin-block-end: 0rem;">Search by :</h3>
                        <div class="col-md-3 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="nameInput" placeholder="Name">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phoneInput" placeholder="Phone">
                        </div>
                        {{-- <div class="col-md-3 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="emailInput" placeholder="Email">
                        </div> --}}
                        <div class="col-md-3 mb-3">
                            <label for="date" class="form-label">From</label>
                            <input type="date" class="form-control" id="dateFromInput" placeholder="Min Date">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="date" class="form-label">To</label>
                            <input type="date" class="form-control" id="dateToInput" placeholder="Max Date">
                        </div>
                        {{-- <div class="col-md-3 mb-3">
                            <label for="targets" class="form-label">Targets</label>
                            <select class="form-select form-select select2" id="targetsInput">
                                <option value="None">None</option>
                                @foreach($targetgroups as $targetgroup)
                                <option value="{{number_format($targetgroup['amount'])}}">{{$targetgroup['name']." - KSh ".number_format($targetgroup['amount'])}}</option>
                                @endforeach
                            </select>

                        </div> --}}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="responsive-datatable" class="table table-bordered text-nowrap border-bottom">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th id="edit-column">View/Edit</th>
                                        <th id="delete-column">Delete</th>
                                        @if(auth()->user()->usertype == 'systemadmin')
                                        <th>Organization</th>
                                        @endif
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        {{-- <th>Usertype</th> --}}
                                        {{-- <th>Targets</th> --}}
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                        @if(count($users)>0)
                                        
                                        @foreach($users as $user)
                                        <tr>
                                        <td>{{$user['id']}}</td>
                                        <td>
                                            <a type="button" href="/editadmin/{{$user['id']}}" class="btn btn-info" style="margin-left: auto;color:white;">View/Edit</a>
                                        </td>
                                        <td><a type="button" onclick="deleteItem({{$user['id']}},'Id: '+{{$user['id']}}+'<br/> Name: '+'{{$user['name']}}'+'<br/> Phone: '+'{{$user['phone']}}'+'<br/> Email: '+'{{$user['email']}}')"  class="btn btn-danger" style="margin-left: auto;color:white;" data-bs-toggle="modal" href="#modalDelete">Delete</a>
                                        </td>
                                        @if(auth()->user()->usertype == 'systemadmin')
                                        <th>{{$user['organization']['name']}}</th>
                                        @endif
                                        <td>{{$user['name']}}</td>
                                        <td>{{$user['phone']}}</td>
                                        <td>{{$user['email']}}</td>
                                        <td>{{ucfirst($user['status'])}}</td>
                                        <td>{{date('d-M-Y', strtotime($user['created_at']))}}</td>
                                        {{-- <td>{{ucfirst($user['usertype'])}}</td> --}}
                                        {{-- <td>{{$user['targetgroup']==null?"None":'KSh '.number_format($user['targetgroup']['amount'])}}</td> --}}
                                        
                                        <!--<a class="modal-effect btn btn-primary-light d-grid mb-3" data-bs-effect="effect-scale" >Scale</a>-->
                                        </tr>
                                        @endforeach
                                        @else
                                        <!--<tr><td colspan="7" style="text-align: center">No users so far</td></tr>-->
                                        <!--let datatables display no data available-->
                                        @endif
                                    
                                </tbody>
                                {{-- <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Targets</th>
                                        <th>View/Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </tfoot> --}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>


                        



    </div>
</div>

    <!-- MODAL EFFECTS -->
    <div class="modal fade" id="modalDelete">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Delete</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <h6>Are you sure you want to delete?</h6>
                    <strong id="itemDetails">Details</strong>
                </div>
                <div class="modal-footer">
                    <a id="confirmDelete" class="btn btn-danger" style="color: white">Delete</a> <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


@endsection

<script>
    function deleteItem(id,message){
        //alert(id);
        var itemDetails = document.getElementById("itemDetails");
        itemDetails.innerHTML = message;
        var confirmDeleteLink = document.getElementById('confirmDelete'); //or grab it by tagname etc
        confirmDeleteLink.href = "/deleteadmin/"+id;
    }
  </script>

  
            {{-- <!-- JQUERY JS -->
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
  <!-- DATA TABLE JS-->
  <script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
  <script src="{{asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
  <script src="{{asset('assets/plugins/datatable/js/buttons.bootstrap5.min.js')}}"></script>
  <script src="{{asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
  <script src="{{asset('assets/plugins/datatable/pdfmake/pdfmake.min.js')}}"></script>
  <script src="{{asset('assets/plugins/datatable/pdfmake/vfs_fonts.js')}}"></script>
  <script src="{{asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
  <script src="{{asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
  <script src="{{asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
  <script src="{{asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
  <script src="{{asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>
  <script src="{{asset('assets/js/table-data.js')}}"></script> --}}

  <script>
    // alert('here');



    // var table = $('#responsive-datatable').DataTable({
    //     initComplete: function () {
    //         alert('it works responsive');
    //         // this.api()
    //         //     .columns()
    //         //     .every(function () {
    //         //         let column = this;
    //         //         let title = column.footer().textContent;
     
    //         //         // Create input element
    //         //         let input = document.createElement('input');
    //         //         input.placeholder = title;
    //         //         column.footer().replaceChildren(input);
     
    //         //         // Event listener for user input
    //         //         input.addEventListener('keyup', () => {
    //         //             if (column.search() !== this.value) {
    //         //                 column.search(input.value).draw();
    //         //             }
    //         //         });
    //         //     });
    //     }
    // });
    // $('#responsive-datatable tbody').on('click', 'tr', function () {
    //     var data = table.row(this).data();
    //     alert('You clicked on ' + data[0] + '\'s row');
    // });
                </script>





