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
     onclick="rewriteMap({{ $location }})"
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
                            <div id="googleMapLocations" class="worldh world-map h-500" ></div>
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
    var distanceDiv;
    
    function myMap2() {
        var locations= @json($locations);

    if(locations[0] != null){
    var mapProp= {
      center:new google.maps.LatLng(Number(locations[0]['start_lat']),
      Number(locations[0]['start_long'])),
      zoom:10,
    };
    // alert("working well");
    }else{
        var mapProp= {
      center:new google.maps.LatLng(-1.286389,
      36.817223),
      zoom:10,
    };
    //alert("we are here");

    }
    document.addEventListener("DOMContentLoaded", function() {
      
    
   var googleMapsDiv= document.getElementById("googleMapLocations");

   distanceDiv= document.getElementById("distance");


    
    map = new google.maps.Map(googleMapsDiv,mapProp);
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);
    // alert("we get here");
    var waypoints = [];
 
    // if(locations.length>0){
    // var bounds = new google.maps.LatLngBounds();
    
    // locations[0]['locationitems'].forEach(locationitem => {
    //     var lat1 = Number(locationitem['outlet']['lat']);
    //     var long1 = Number(locationitem['outlet']['long']);
    
    //     waypoints.push({ location: { lat: lat1, lng: long1 }, stopover: true });

    //     const myPosition = { lat: lat1, lng: long1 };
    //     console.log(myPosition);

    //     // var markerElement = new google.maps.marker.AdvancedMarkerElement({
    //     //         position: myPosition,
    //     //         map: map,
    //     //         title: 'Visit Marker', // Add a title if needed
    //     //     });

    //         // Extend the bounds to include this marker's position
    //         bounds.extend(myPosition);

    //         // var infoContent = "<div><h3>"+visit['outlet']['name']+"</h3>"+
    //         //     "<p>Latitude: "+lat1+"</p>"+
    //         //     "<p>Longitude: "+long1+"</p>"+
    //         //     "<p>Seller: "+visit['user']['name']+"</p>"+
    //         // "</div>";

    //         // Create an InfoWindow
    //         // var infoWindow = new google.maps.InfoWindow({
    //         //     content: infoContent
    //         // });
    //         //
    //         var infowindow = new google.maps.InfoWindow({
    //             content: "<div><h3>"+locationitem['outlet']['name']+"</h3>"+
    //                 // "<p>Seller: "+( locationitem['outlet']['user']===null ? 'Deleted User': locationitem['outlet']['user']['name'])+"</p>"+
    //                 "<p>Latitude: "+lat1+"</p>"+
    //                 "<p>Longitude: "+long1+"</p>"+
    //                 "</div>"
    //         });

    //         // Add a click event listener to the marker to open the InfoWindow
    //         // marker.addListener('click', function() {
    //         //     infoWindow.open(map, marker);
    //         // });
            
        
       
    //     // var marker = new google.maps.Marker({position: myPosition,
    //     //     title: locationitem['outlet']['name'],
    //     // });
    //     // google.maps.event.addListener(marker, 'click', function() {
    //     //         infowindow.open(map,marker);
    //     //     });
    //     // marker.setMap(map);
        
    // });
    // map.fitBounds(bounds);
    
    //             const origin = waypoints.shift().location;
    //             const destination = waypoints.pop().location;

    //             const request = {
    //                 origin: origin,
    //                 destination: destination,
    //                 waypoints: waypoints,
    //                 optimizeWaypoints: true,
    //                 travelMode: 'DRIVING'
    //             };

    //             directionsService.route(request, function(result, status) {
    //                 if (status == 'OK') {
    //                     directionsRenderer.setDirections(result);
    //                     console.log('Route drawn with result:', result);
                       
    //                 } else {
    //                     console.error('Directions request failed due to ' + status);
    //                 }
    //             });









    // var lat1 = locations[0]['locationitems'][0]['outlet']['lat'];
    // var long1 = locations[0]['locationitems'][0]['outlet']['long'];
    // }

});
   // console.log(recentVisits);
    
    // const myPosition = { lat: -25.344, lng: 131.031 };
    // //console.log(myPosition);
    // const myPosition2 = { lat: Number(lat1), lng: Number(long1) };
    // //console.log(myPosition2);
    // var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
    // var marker = new google.maps.Marker({position: myPosition});
    // marker.setMap(map);
    // var marker2 = new google.maps.Marker({position: myPosition2});
    // marker2.setMap(map);
    }

    var markers = [];
    var locations = [];

    function rewriteMap(location){
        
        //alert('we get here');
        //alert (location['locationitems'][0]['outlet']['lat']);
        distanceDiv.innerText = "";

        
        //console.log(location);
        waypoints=[];
        if(markers==undefined){
            // console.log(markers);
            // alert("undefined");
        }else{
            // alert("defined");
            // console.log(markers);
            markers.forEach(marker => {
                marker.setMap(null);
            });
            locations = [];
            markers = [];

            if (directionsRenderer.getMap()) {
            directionsRenderer.set('directions', null); // Clear previous route
            }
        }
        
 

        waypoints.push({ location: { lat: Number(location.start_lat) , lng: Number(location.start_long) }, stopover: true });
                // Adding a new location
        locations.push({
            "lat": Number(location.start_lat),
            "long": Number(location.start_long)
        });
        var  count = 0;
        const totalLocations = location.locations.length;
        const step = Math.ceil(totalLocations / 20); // Calculate step size

        location.locations.forEach((location, index) => {
            if (index % step === 0) { // Push every 'step'-th location
                waypoints.push({
                    location: { lat: Number(location.lat), lng: Number(location.long) },
                    stopover: true
                });
                locations.push({
                "lat": Number(location.lat),
                "long": Number(location.long)
            });
            }
 
        });





        
       
        console.log('Map instance:', map);

       
        
        if(location.end_lat==null){
            // alert("endpoint undefined")
            // const origin = waypoints.shift().location;

            var bounds = new google.maps.LatLngBounds();
            addMarkers(map,locations,bounds);
            map.fitBounds(bounds);

        }else{
            waypoints.push({ location: { lat: Number(location.end_lat), lng: Number(
                location.end_long) }, stopover: true });
            locations.push({
                "lat": Number(location.end_lat),
                "long": Number(location.end_long)
            });

            const origin = waypoints.shift().location;
            const destination = waypoints.pop().location;

            const request = {
                origin: origin,
                destination: destination,
                waypoints: waypoints,
                optimizeWaypoints: true,
                travelMode: 'DRIVING'
            };

            directionsService.route(request, function(result, status) {
            if (status == 'OK') {

                const route = result.routes[0];
                let totalDistance = 0;

                // Loop through each leg to calculate the total distance
                route.legs.forEach(leg => {
                    totalDistance += leg.distance.value; // distance.value is in meters
                });

                distanceDiv.innerText = ": Distance : "+totalDistance/1000+" km"+" : Fuel Estimate (Assuming KSh 70/km) in Litres: "+70*totalDistance/1000+" ";


                if (totalDistance < 50) {
                    console.log('Route is less than 50 meters, not drawing directions.');
                    var bounds = new google.maps.LatLngBounds();
                    addMarkers(map,locations,bounds);
                    map.fitBounds(bounds);
                } else {
                    directionsRenderer.setDirections(result);
                    console.log('Route drawn with result:', result);
                    addMarkers(map,locations);
                }

            } else {
                console.error('Directions request failed due to ' + status);
                 var bounds = new google.maps.LatLngBounds();
                addMarkers(map,locations,bounds);
                map.fitBounds(bounds);
            }
        });

        }


        

        function addMarkers(map,markerpointsset,bounds) {
            var itemNumber = 1;

            //should be google.maps.Marker.MAX_ZINDEX + 1 but we're using 
            //google.maps.Marker.MAX_ZINDEX + itemNumber to overlay multiple on the same for now

            markerpointsset.forEach(item => {
                //alert('adding marker');
                var marker = new google.maps.Marker({
                    position: { lat: parseFloat(item.lat), lng: parseFloat(item.long) },
                    map: map,
                    label: String(itemNumber),
                    title: 'Marker',
                    zIndex: google.maps.Marker.MAX_ZINDEX + itemNumber
                });
                itemNumber++;

                if(bounds!=undefined){
                const waypointPosition = { lat: parseFloat(item.lat), lng: parseFloat(item.long) };
                console.log(waypointPosition);
                // Extend the bounds to include this marker's position
                bounds.extend(waypointPosition);
                }

                //marker.setMap(map);

                var infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div>
                            <p><strong>Latitude:</strong> ${item.lat}</p>
                            <p><strong>Longitude:</strong> ${item.long}</p>
                        </div>
                    `
                });

                marker.addListener('click', function () {
                    infoWindow.open(map, marker);
                });

                markers.push(marker);

               
            });
            
        }


        


    // document.addEventListener("DOMContentLoaded", function() {
    //     console.log('Map instance:', map);
    //     alert('we get here too');

    // // function rewriteMap(){
        
    // // }
    
    // });
    
    }
    

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=
    AIzaSyALLsNWwOC09xsRAqrK0S7dINi6BpNc7iw&callback=myMap2"></script>

