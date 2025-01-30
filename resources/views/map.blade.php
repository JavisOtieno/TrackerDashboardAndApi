@extends('layout')

@section('content')

<div class="side-app">

    <!-- CONTAINER -->
    <div class="main-container container-fluid">

  <!-- PAGE-HEADER -->
  <div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">RTMS</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->








     <div class="row">
 
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card overflow-hidden">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Recent Visits</h3>
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

     


     <script>
        function myMap() {
            var locations= @json($locations);

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
                var infowindow = new google.maps.InfoWindow({
                    content: "<div><h3>"+
                    // visit['outlet']['name']+"</h3>"+
                        // "<p>Seller: "+( visit['user']===null ? 'Deleted User': visit['user']['name'])+"</p>"+
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
        

        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=
        AIzaSyALLsNWwOC09xsRAqrK0S7dINi6BpNc7iw&callback=myMap"></script>
            {{-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyALLsNWwOC09xsRAqrK0S7dINi6BpNc7iw&callback=initMap"></script> --}}



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
