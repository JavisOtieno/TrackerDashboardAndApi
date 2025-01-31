@extends('layout')

@section('content')

<div class="side-app">

    <!-- CONTAINER -->
    <div class="main-container container-fluid">

  <!-- PAGE-HEADER -->
  <div class="page-header">
    <h1 class="page-title">Trail Map</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Tracker</a></li>
            <li class="breadcrumb-item active" aria-current="page">Trail Map</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->








     <div class="row">
 
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card overflow-hidden">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Locations</h3>
                    </div>
                </div>
                <div class="card-body p-0 mt-2">
                    <div class="m-2" >
                        {{-- <div id="world-map-markers1" class="worldh world-map h-250"></div> --}}
                        <div id="googleMap" class="worldh world-map h-500" ></div>
                    </div>
     
                </div>
            </div>
        </div>

    </div>

     


     {{-- <script>
        function myMap() {
            var locations= @json($locations);
            locations = locations.filter(location => location.id > 460);

        if(locations[0]){
            var mapProp= {
            center:new google.maps.LatLng(Number(locations[0]['lat']),
            Number(locations[0]['long'])),
            zoom:10,
            };
        }else{
            var mapProp= {
            center:new google.maps.LatLng(-1.286389,36.817223),
            zoom:10,
            };
        }
        
        var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);

        if(locations.length>0){
        var bounds = new google.maps.LatLngBounds();
        locations.forEach(location => {
            var lat1 = Number(location['lat']);
            var long1 = Number(location['long']);

            const myPosition = { lat: lat1, lng: long1 };
            console.log(myPosition);

            // var markerElement = new google.maps.marker.AdvancedMarkerElement({
            //         position: myPosition,
            //         map: map,
            //         title: 'Visit Marker', // Add a title if needed
            //     });

                // Extend the bounds to include this marker's position
                bounds.extend(myPosition);

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
                var date = new Date(location.created_at);
                var hours = date.getHours();
                var minutes = date.getMinutes();
                var day = date.getDate();
                var month = date.getMonth(); // Months are zero-based, so add 1
                var year = date.getFullYear();

                var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

                // Get month name from array
                var monthName = monthNames[month];

                var locationDate = day + '-' + monthName + '-' + year;

                // Pad single digit minutes with a leading zero
                minutes = minutes < 10 ? '0' + minutes : minutes;

                hours = hours < 10 ? '0' + hours : hours;

                var locationTime = hours + ':' + minutes;
                var infowindow = new google.maps.InfoWindow({
                    content: "<div>"+
                    // visit['outlet']['name']+"</h3>"+
                        // "<p>Seller: "+( visit['user']===null ? 'Deleted User': visit['user']['name'])+"</p>"+
                        "<h3>Date: "+locationDate+"</h3>"+
                        "<p>Time: "+locationTime+"</p>"+
                        "<p>Latitude: "+lat1+"</p>"+
                        "<p>Longitude: "+long1+"</p>"+
                        "</div>"
                });

                // Add a click event listener to the marker to open the InfoWindow
                // marker.addListener('click', function() {
                //     infoWindow.open(map, marker);
                // });
                
            
           
            var marker = new google.maps.Marker({position: myPosition,
                // title: visit['outlet']['name'],
                label: String(location.id),
                title: 'test',
                zIndex: google.maps.Marker.MAX_ZINDEX + Number(location.id)
            });
            google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map,marker);
                });
            marker.setMap(map);
            
        });
        map.fitBounds(bounds);
        var lat1 = locations[0]['lat'];
        var long1 = locations[0]['long'];
        }
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
        

        </script> --}}
        
            {{-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyALLsNWwOC09xsRAqrK0S7dINi6BpNc7iw&callback=initMap"></script> --}}


            <script>
                // Global variables to track map state
                let map;
                let markers = [];
                let updateInterval = 30000; // 30 seconds
                let updateTimer = null;
                let routePolyline = null; // Add this line
                
                async function initializeMap() {
                    // Initial setup with server-rendered data
                    let locations = @json($locations);
                    locations = locations.filter(location => location.id > 460);
                
                    // Map configuration
                    const mapProp = locations[0] ? {
                        center: new google.maps.LatLng(
                            Number(locations[0].lat),
                            Number(locations[0].long)
                        ),
                        zoom: 10
                    } : {
                        center: new google.maps.LatLng(-1.286389, 36.817223),
                        zoom: 10
                    };
                
                    // Create map instance once
                    if (!map) {
                        map = new google.maps.Map(
                            document.getElementById("googleMap"),
                            mapProp
                        );
                    }
                
                    // Process initial locations
                    processLocations(locations);
                    
                    // Start update cycle
                    if (!updateTimer) {
                        updateTimer = setInterval(fetchAndUpdateLocations, updateInterval);
                    }
                }
                
                async function fetchAndUpdateLocations() {
                    try {
                        const response = await fetch('/api'); // Use correct endpoint
                        const newLocations = await response.json();
                        // const filtered = newLocations.filter(location => location.id > 460);
                        
                        clearMarkers();
                        processLocations(newLocations);
                    } catch (error) {
                        console.error('Update failed:', error);
                        // Optional: Implement retry logic
                    }
                }
                
                function processLocations(locations) {
                    if (!locations.length) return;
                
                    const bounds = new google.maps.LatLngBounds();
                    const path = []; // Array to store coordinates for polyline
                    var waypoints = [];
                    
                    locations.forEach(location => {
                        const lat1 = Number(location.lat);
                        const long1 = Number(location.long);
                        const position = { lat: lat1, lng: long1 };
                        // waypoints.push({ location: position, stopover: true });
                        waypoints.push({ location: { lat: Number(location.lat), 
                            lng: Number(location.long) }, stopover: true });
                        // waypoints.push({ location: { lat: Number(routeplanitem.outlet.lat),
                        //      lng: Number(routeplanitem.outlet.long) }, stopover: true });



                
                        // Create marker
                        const marker = new google.maps.Marker({
                            position: position,
                            label: String(location.id),
                            title: 'Location',
                            zIndex: google.maps.Marker.MAX_ZINDEX + location.id,
                            map: map
                        });
                
                        // Create info window
                        const date = new Date(location.created_at);
                        const infowindow = new google.maps.InfoWindow({
                            content: `
                                <div>
                                    <h3>Date: ${date.toLocaleDateString('en-GB', {
                                        day: 'numeric',
                                        month: 'short',
                                        year: 'numeric'
                                    })}</h3>
                                    <p>Time: ${date.toLocaleTimeString('en-GB', {
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    })}</p>
                                    <p>Latitude: ${lat1}</p>
                                    <p>Longitude: ${long1}</p>
                                </div>
                            `
                        });
                
                        // Add click listener
                        marker.addListener('click', () => {
                            infowindow.open(map, marker);
                        });
                
                        markers.push(marker);
                        path.push(position);
                        bounds.extend(position);
                    });
                    console.log("path");
                    console.log(path);

                    //     // Remove existing polyline if it exists
                    // if (routePolyline) {
                    //     routePolyline.setMap(null);
                    // }

                    // // Create new polyline
                    // routePolyline = new google.maps.Polyline({
                    //     path: path,
                    //     geodesic: true,
                    //     strokeColor: "#FF0000", // Red color
                    //     strokeOpacity: 1.0,
                    //     strokeWeight: 2,
                    //     map: map
                    // });

                    const origin = waypoints.shift().location;
                const destination = waypoints.pop().location;
                waypoints = [];
                console.log("origin");
                console.log(origin);
                console.log("origin");

                const request = {
                    origin: origin,
                    destination: destination,
                    waypoints: waypoints,
                    optimizeWaypoints: true,
                    travelMode: 'DRIVING'
                };

                var directionsService = new google.maps.DirectionsService();
                var directionsRenderer = new google.maps.DirectionsRenderer();
                directionsRenderer.setMap(map);

                directionsService.route(request, function(result, status) {
                    if (status == 'OK') {

                        const route = result.routes[0];
                        let totalDistance = 0;

                        // Loop through each leg to calculate the total distance
                        route.legs.forEach(leg => {
                            totalDistance += leg.distance.value; // distance.value is in meters
                        });

                        if (totalDistance < 50) {
                            console.log('Route is less than 50 meters, not drawing directions.');
                            var bounds = new google.maps.LatLngBounds();
                            // addMarkers(map,routeplan.routeplanitems,bounds);
                            map.fitBounds(bounds);
                        } else {
                            directionsRenderer.setDirections(result);
                            console.log('Route drawn with result:', result);
                            // addMarkers(map,routeplan.routeplanitems);
                        }

                    } else {
                        console.error('Directions request failed due to ' + status);
                        // var bounds = new google.maps.LatLngBounds();
                        // addMarkers(map,routeplan.routeplanitems,bounds);
                        // map.fitBounds(bounds);
                    }
                });
                    
                        map.fitBounds(bounds);
                    }
                
                function clearMarkers() {
                    if (routePolyline) {
                        routePolyline.setMap(null);
                        routePolyline = null;
                    }
                    markers.forEach(marker => marker.setMap(null));
                    markers = [];
                }
                
                // Initialize when Google Maps API loads
                function myMap() {
                    initializeMap();
                }
                
                // Cleanup when page unloads
                window.addEventListener('beforeunload', () => {
                    if (updateTimer) clearInterval(updateTimer);
                });
                </script>
                
                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyALLsNWwOC09xsRAqrK0S7dINi6BpNc7iw&callback=myMap"></script>

    <?php
    // $string ='[';
    
    //             foreach ($salesPerMonth as $sale){
    //                 //echo $sale;
    //                 if($string=='['){ $string =$string.=''.$sale.''; }
    //                 else{
    //                 $string =$string.=','.$sale.'';
    //                 }
    //             }
                
    //             $string=$string.']';

    //             for ($i = 0; $i <= 11; $i++) {
    //             $months[] = date("M", strtotime( date( 'Y-m-01' )." -$i months"));
    //             }
    //             //echo "Before";
    //             //print_r($months);
    //             //echo "After";
    //             $months=array_reverse($months);
    //             //print_r($months);
    //             $stringMonths ='[';
    //             foreach ($months as $month){
    //                 //echo $sale;
    //                 if($stringMonths=='['){ $stringMonths =$stringMonths.='"'.$month.'"'; }
    //                 else{
    //                     $stringMonths =$stringMonths.=',"'.$month.'"';
    //                 }
                    
    //             }
    //             $stringMonths=$stringMonths.']';
    //             //echo $stringMonths;
                
    //             //echo $string;
    //             //print_r($visitsCount);
    //             $stringVisits ='[';
   
    //             foreach ($visitsCount as $visit){
    //                 //echo $sale;
    //                 if($stringVisits=='['){ $stringVisits =$stringVisits.=''.$visit.''; }
    //                 else{
    //                 $stringVisits =$stringVisits.=','.$visit.'';
    //             }
    //             }
                
    //             $stringVisits=$stringVisits.']';
    //             //echo $stringVisits;
                ?>

    
<!-- INTERNAL CHARTJS CHART JS-->
<script src="{{asset('assets/plugins/chart/Chart.bundle.js')}}"></script>
<script src="{{asset('assets/plugins/chart/utils.js')}}"></script>
    




        



    @endsection
