@extends('layout')

@section('content')

<div class="side-app">

    <!-- CONTAINER -->
    <div class="main-container container-fluid">

  <!-- PAGE-HEADER -->
  <div class="page-header">
    <h1 class="page-title">Locations</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">RTMS</a></li>
            <li class="breadcrumb-item active" aria-current="page">Locations</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->


<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            {{-- <h3 class="card-title">Create Location</h3> --}}
            {{-- <a type="button" href="/createlocation" class="btn btn-danger col-md-3" 
            style="margin-left: auto;margin-right:20px;margin-top:10px;margin-bottom:10px;color:white;"
            >Create Location</a> --}}
        </div>
        <div class="row" style="margin: 0 10px;">
            <input type="hidden" id="tableLoaded" value="locations"/>
            <h3 class="card-title" style="margin-top:20px;margin-block-end: 0rem;">Search by :</h3>
            {{-- <div class="col-md-3 mb-3">
                <label for="name" class="form-label">Test Name</label>
                <input type="text" class="form-control" id="nameInput" placeholder="Name">
            </div> --}}
            <div class="col-md-3 mb-3">
                <label for="locationName" class="form-label">Location Name</label>
                <input type="text" class="form-control" id="locationNameInput" placeholder="Location Name">
            </div>
            <div class="col-md-3 mb-3">
                <label for="date" class="form-label">From</label>
                <input type="date" class="form-control" id="dateFromInput" placeholder="Min Date">
            </div>
            <div class="col-md-3 mb-3">
                <label for="date" class="form-label">To</label>
                <input type="date" class="form-control" id="dateToInput" placeholder="Max Date">
            </div>
            
            
        </div>
        <div class="card-body">
            <!--<p>Use <code class="highlighter-rouge">.table-slocationed</code>to add zebra-slocationing to any table row within the <code class="highlighter-rouge">.tbody</code>.</p>-->
            <div class="table-responsive">
                <table id="responsive-datatable" class="table table-bordered text-nowrap border-bottom">
                    <thead>
                        <tr>
                            <th>ID</th>
                            
                            {{-- <th>Test Name</th> --}}
                            <th>Driver</th>
                            <th>Lat</th>
                            <th>Long</th>
                            <th>Date</th>
                            <th>Time</th>
                    
                            
                            <th id="map-column">Map</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($locations)>0)
                        
                        @foreach($locations as $location)
<tr>
<td>{{$location['id']}}</td>

    {{-- <td>{{$posaudit['user']['name']}}</td> --}}
<td>{{ $location['user'] ? $location['user']['name']  . ($location['user']->trashed() ? ' (Deleted User)' : '') : 'Deleted User'}}
<td>{{$location['lat']}}</td>
<td>{{$location['long']}}</td>
<td>{{date('d-M-Y', strtotime($location['created_at']))}}</td>
<td>{{date('H:i', strtotime($location['created_at']))}}</td>



<td><a type="button" 
    {{-- onclick="embedMap2()" --}}
    id=""
     onclick="loadPointOnMap({{ $location }})"
    class="btn btn-success" style="margin-left: auto;color:white;" data-bs-toggle="modal" href="#largemodal">Map</a>
</td>
{{-- <td>
    <form method="POST" action="{{ route('editproduct') }}">
        @csrf
        <input type="hidden" name="id" value="{{ $product->id }}">
        <!-- Other form fields -->
        <button type="submit" class="btn btn-info">View</button>
    </form>
</td> --}}

</tr>
@endforeach

@else
                                    <!--<tr><td colspan="7" style="text-align: center">No sales so far</td></tr>-->
                                    <!--let datatables display no data available-->
                                    @endif

                        
                           
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

            <!-- Modal -->
            <div class="modal fade" id="largemodal">
                <div class="modal-dialog modal-lg " role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Map</h5>
                            <h5 id="distance" class="modal-title"></h5>
                            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                        </div>
                    
                        <div class="modal-body">
                            <div id="googleMapLocation" class="worldh world-map h-500" ></div>
                            {{-- <iframe id="mapEmbededForNow" style="width:100%;height:400px;" src = "https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d4084493.7307095355!2d36.50569875426991!3d-1.3175850003645804!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2ske!4v1715327211380!5m2!1sen!2ske"></iframe> --}}
                            <!--<div id="googleMap" style="width:100%;height:400px;"></div>-->
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            {{-- <button class="btn btn-primary">Save changes</button> --}}
                        </div>
                    </div>
                    
                </div>
            </div>

@endsection

<script>
    function deleteItem(id,name,seller){
        //alert(id);
        var itemDetails = document.getElementById("itemDetails");
        itemDetails.innerHTML = "Location Name: "+name+"<br/> Seller Name: "+seller;
        var confirmDeleteLink = document.getElementById('confirmDelete'); //or grab it by tagname etc
        confirmDeleteLink.href = "/deletelocation/"+id;
    }
  </script>

{{-- <script>

function embedMap(lat,long){
        //alert(id);
        var mapEmbedFrame = document.getElementById("mapEmbededForNow");
        mapEmbedFrame.src = "https://maps.google.com/maps?q="+lat+","+long+"&hl=es;z=14&amp&output=embed";
        //var confirmDeleteLink = document.getElementById('confirmDelete'); //or grab it by tagname etc
        //confirmDeleteLink.href = "/deletesale/"+id;
    }
    
    function embedMap2() {
        //alert("we get here");
        var mapProp= {
      center:new google.maps.LatLng(-1.286389,
      36.817223),
      zoom:10,
    };
    var locations= @json($locations);
    

    if(locations[0]['locationitems'][0]['outlet']){
        alert("we get here location yeah");
    var mapProp= {
      center:new google.maps.LatLng(Number(locations[0]['locationitems'][0]['outlet']['lat']),
      Number(locations[0]['locationitems'][0]['outlet']['long'])),
      zoom:10,
    };
    }else{
        alert("we get here!!!!!");
        var mapProp= {
      center:new google.maps.LatLng(-1.286389,
      36.817223),
      zoom:10,
    };
    //alert("we are here");

    }
    document.addEventListener("DOMContentLoaded", function() {
      
    alert("before we get ahead of ourselves");
   var googleMapsDiv= document.getElementById("mapEmbededForNow");
   alert("we dont need the dom yeah");


    
    var map = new google.maps.Map(googleMapsDiv,mapProp);
    if(locations.length>0){
        alert("we get here with the plans. wuuhuu");
    var bounds = new google.maps.LatLngBounds();

    console.log(locations);
    
    locations.forEach(location => {
        var lat1 = Number(location['locationitems'][0]['outlet']['lat']);
        var long1 = Number(location['locationitems'][0]['outlet']['long']);
        alert(lat1);
        alert(long1);

        // const myPosition = { lat: lat1, lng: long1 };
        //console.log(myPosition);

        // var markerElement = new google.maps.marker.AdvancedMarkerElement({
        //         position: myPosition,
        //         map: map,
        //         title: 'Visit Marker', // Add a title if needed
        //     });

            // Extend the bounds to include this marker's position
         //   bounds.extend(myPosition);

            // var infoContent = "<div><h3>"+visit['outlet']['name']+"</h3>"+
            //     "<p>Latitude: "+lat1+"</p>"+
            //     "<p>Longitude: "+long1+"</p>"+
            //     "<p>Seller: "+visit['user']['name']+"</p>"+
            // "</div>";

            // Create an InfoWindow
            // var infoWindow = new google.maps.InfoWindow({
            //     content: infoContent
            // });
            //
            // var infowindow = new google.maps.InfoWindow({
            //     content: "<div><h3>"+location['locationitems'][0]['outlet']['name']+"</h3>"+
            //         "<p>Latitude: "+lat1+"</p>"+
            //         "<p>Longitude: "+long1+"</p>"+
            //         "</div>"
            // });

            // Add a click event listener to the marker to open the InfoWindow
            // marker.addListener('click', function() {
            //     infoWindow.open(map, marker);
            // });
            const myPosition = { lat: -25.344, lng: 131.031 };
            
        
        alert(location['locationitems'][0]['outlet']['name']);
        var marker = new google.maps.Marker({position: myPosition,
            title: location['locationitems'][0]['outlet']['name'],
        });
        // google.maps.event.addListener(marker, 'click', function() {
        //         infowindow.open(map,marker);
        //     });
        marker.setMap(map);
        
    });
    //map.fitBounds(bounds);
    var lat1 = locations[0]['locationitems'][0]['outlet']['lat'];
    var long1 = locations[0]['locationitems'][0]['outlet']['long'];
    }

    });

   // console.log(recentVisits);
    

    // console.log(myPosition);
    // const myPosition2 = { lat: Number(lat1), lng: Number(long1) };
    // console.log(myPosition2);
    // var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
    // var marker = new google.maps.Marker({position: myPosition});
    // marker.setMap(map);
    // var marker2 = new google.maps.Marker({position: myPosition});
    // marker2.setMap(map);
    }

    

    </script>

<script src="https://maps.googleapis.com/maps/api/js?key=
AIzaSyALLsNWwOC09xsRAqrK0S7dINi6BpNc7iw&callback=embedMap2"></script> --}}

<script>
    var map;
    var marker;
    
    function myMap2() {


        var mapProp= {
      center:new google.maps.LatLng(-1.3,
      36.8),
      zoom:10,
    };

    document.addEventListener("DOMContentLoaded", function() {
    
   var googleMapsDiv= document.getElementById("googleMapLocation");
    
    map = new google.maps.Map(googleMapsDiv,mapProp);

            
});


    }


    function loadPointOnMap(location){
        

        if(marker==undefined){
            // console.log(markers);
            // alert("undefined");
        }else{
                marker.setMap(null);

        }
        

        //waypoints.push({ location: { lat: -1.244, lng: 36.8 }, stopover: true });
        console.log('Map instance:', map);


        //should be google.maps.Marker.MAX_ZINDEX + 1 but we're using 
        //google.maps.Marker.MAX_ZINDEX + itemNumber to overlay multiple on the same for now

        var locationPosition = { lat: parseFloat(location.lat), lng: parseFloat(location.long) };

            //alert('adding marker');
            marker = new google.maps.Marker({
                position: locationPosition,
                map: map,
                title: location.user ? location.user.name : "N/A"
            });

            map.setCenter(locationPosition);
            map.setZoom(12);

            var date = new Date(location.created_at);
            var hours = date.getHours();
            var minutes = date.getMinutes();

            // Pad single digit minutes with a leading zero
            minutes = minutes < 10 ? '0' + minutes : minutes;

            var locationTime = hours + ':' + minutes;

            //alert(clockinTime);

            var infoWindow = new google.maps.InfoWindow({
                content: `
                    <div>
                        <h3>${location.user.name}</h3>
                        <p><strong>Time:</strong> ${locationTime}</p>
                        <p><strong>Latitude:</strong> ${location.lat}</p>
                        <p><strong>Longitude:</strong> ${location.long}</p>
                        <p><strong>View:</strong> <a href='/location/${location.id}' >View Clockout Details</a></p>
                    </div>
                `
            });

            // Open the info window
            let isInfoWindowOpen = true;
            infoWindow.open(map,marker);

            //added the toggle functionality here because for this case the map opens up the infoview on load
            
            marker.addListener('click', function () {
                if (isInfoWindowOpen) {
                    infoWindow.close(); // Close the InfoWindow if it's open
                    isInfoWindowOpen = false; // Update the state
                } else {
                    infoWindow.open(map, marker); // Open the InfoWindow if it's closed
                    isInfoWindowOpen = true; // Update the state
                }
            });

            

    }
    

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=
    AIzaSyALLsNWwOC09xsRAqrK0S7dINi6BpNc7iw&callback=myMap2"></script>

