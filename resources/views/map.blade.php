<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    

<div class="side-app">

    <!-- CONTAINER -->
    <div class="main-container container-fluid">

  <!-- PAGE-HEADER -->
  <div class="page-header">
    <h1 class="page-title">Locations Map</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">RTMS</a></li>
            <li class="breadcrumb-item active" aria-current="page">Locations map</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->


<div class="col-xl-12">

    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">Locations Map</h3>
            <a type="button" href="/add-location" class="btn btn-danger col-md-3" style="margin-left: auto;margin-right:20px;margin-top:10px;margin-bottom:10px;color:white;">Add Location</a>
        </div> --}}


        <div class="card-body">
            <div>
                <div id="googleMapLocations" class="worldh world-map h-500" ></div>
            </div>
        </div>
    </div>
</div>

    </div>
</div>
</body>
</html>

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

            <!-- Modal -->
            <div class="modal fade" id="largemodal">
                <div class="modal-dialog modal-lg " role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Map</h5>
                            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                        </div>
                        <div class="modal-body">
                            <iframe id="mapEmbededForNow" style="width:100%;height:400px;" src = "https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d4084493.7307095355!2d36.50569875426991!3d-1.3175850003645804!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2ske!4v1715327211380!5m2!1sen!2ske"></iframe>
                            <!--<div id="googleMap" style="width:100%;height:400px;"></div>-->
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                    
                </div>
            </div>
    
<script>
    function deleteItem(id,name,sku,pieces){
        //alert(id);
        var itemDetails = document.getElementById("itemDetails");
        itemDetails.innerHTML = "Location Id: "+id+"<br/> Location Name: "+name;
        var confirmDeleteLink = document.getElementById('confirmDelete'); //or grab it by tagname etc
        confirmDeleteLink.href = "/deletelocation/"+id;
    }
    function embedMap(lat,long){
        //alert(id);
        var mapEmbedFrame = document.getElementById("mapEmbededForNow");
        mapEmbedFrame.src = "https://maps.google.com/maps?q="+lat+","+long+"&hl=es;z=14&amp&output=embed";
        //var confirmDeleteLink = document.getElementById('confirmDelete'); //or grab it by tagname etc
        //confirmDeleteLink.href = "/deletesale/"+id;
    }
  </script>

  

  


<script>
    function myMap() {
        var locations= @json($locations);

    if(locations[0]){
        // alert(locations[0]['long'])
        var mapProp= {
        center:new google.maps.LatLng(Number(locations[0]['lat']),
        Number(locations[0]['long'])),
        zoom:10,
        };
    }else{
        // alert('no location 0')
        var mapProp= {
        center:new google.maps.LatLng(-1.286389,36.817223),
        zoom:10,
        };
    }
    
    var map = new google.maps.Map(document.getElementById("googleMapLocations"),mapProp);

    if(locations.length>0){
    // alert('locations found')
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
        //     })

            // Extend the bounds to include this marker's position
            bounds.extend(myPosition);

            // var infoContent = "<div><h3>"+location['name']+"</h3>"+
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
                content: "<div><h3>Last Location(Name)</h3>"+
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
            // title: location['name'],
            label: String(location.id),
            title: String(location.id),
            // zIndex: google.maps.Marker.MAX_ZINDEX + Number(location.id)
        });
        console.log("markerposition");
        console.log(marker);
        console.log(myPosition)
        console.log("marketposition");
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

